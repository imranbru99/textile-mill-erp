<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Modules\Commercial\Models\Inquiry;
use App\Modules\Commercial\Models\Quotation;
use App\Modules\Commercial\Models\QuotationLine;
use App\Modules\Commercial\Models\SalesOrder;
use App\Modules\Commercial\Models\SalesOrderLine;
use App\Modules\Costing\Models\CostSheet;
use App\Modules\Costing\Models\CostSheetLine;
use App\Modules\Dyeing\Models\DyeBatch;
use App\Modules\Dyeing\Models\DyeBatchChemical;
use App\Modules\Dyeing\Models\FinishingRun;
use App\Modules\Dyeing\Models\LabDip;
use App\Modules\Dyeing\Models\Recipe;
use App\Modules\Dyeing\Models\RecipeLine;
use App\Modules\Finance\Models\ExportRealization;
use App\Modules\Finance\Models\LetterOfCredit;
use App\Modules\Finance\Models\SalesInvoice;
use App\Modules\Finance\Models\SalesInvoiceLine;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Logistics\Models\Shipment;
use App\Modules\Master\Models\Item;
use App\Modules\Master\Models\Machine;
use App\Modules\Master\Models\Shift;
use App\Modules\People\Models\Employee;
use App\Modules\Planning\Models\BomLine;
use App\Modules\Planning\Models\MrpRequirement;
use App\Modules\Planning\Models\ProductionPlan;
use App\Modules\Quality\Models\QualityInspection;
use App\Modules\Spinning\Models\CottonMixing;
use App\Modules\Spinning\Models\CottonMixingLine;
use App\Modules\Spinning\Models\SpinningProduction;
use App\Modules\Spinning\Models\YarnLot;
use App\Modules\Supply\Models\GoodsReceipt;
use App\Modules\Supply\Models\GoodsReceiptLine;
use App\Modules\Supply\Models\PurchaseOrder;
use App\Modules\Supply\Models\PurchaseOrderLine;
use App\Modules\Supply\Models\PurchaseRequisition;
use App\Modules\Supply\Models\PurchaseRequisitionLine;
use App\Modules\Weaving\Models\FabricRoll;
use App\Modules\Weaving\Models\WeavingProduction;
use App\Services\Formulas\DyeRecipeService;
use App\Services\Formulas\InspectionService;
use App\Services\Formulas\OvertimeService;
use App\Services\Formulas\RingFrameProductionService;
use App\Services\Formulas\WeavingCalculator;
use App\Support\Decimal;
use App\Modules\Commercial\Models\Party;
use App\Modules\Foundation\Models\Company;
use App\Modules\Gate\Models\GatePass;
use App\Modules\People\Models\PayrollLine;
use App\Modules\People\Models\PayrollRun;

/** Walks one export fabric order from inquiry to bank realization. */
final class OrderFlowService
{
    public function __construct(
        private readonly NumberSeriesService $numbers,
        private readonly ApprovalService $approvals,
        private readonly StockService $stock,
        private readonly PostingService $posting,
        private readonly CostingService $costing,
        private readonly MixingOptimizer $mixing,
        private readonly IssueEngine $issues,
        private readonly RingFrameProductionService $ring,
        private readonly WeavingCalculator $weaving,
        private readonly DyeRecipeService $dye,
        private readonly InspectionService $inspection,
        private readonly OvertimeService $overtime,
    ) {}

    /**
     * @param  array{company: Company, branchId: int, buyer: Party, supplier: Party, cotton: Item, yarn: Item, fabric: Item, dye: Item, warehouse: Warehouse, ring: Machine, loom: Machine, jet: Machine, user: User, shift: Shift, employee: Employee}  $ctx
     * @return array<string, int|string>
     */
    public function run(array $ctx): array
    {
        $company = $ctx['company'];
        $branchId = $ctx['branchId'];
        $common = ['company_id' => $company->id, 'branch_id' => $branchId];

        $inquiry = Inquiry::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'number' => $this->numbers->next('inquiry', $company->id, $branchId),
            'status' => 'open',
            'document_date' => now()->toDateString(),
            'subject' => '30s cotton poplin, reactive dyed, 10,000 meters',
            'source' => 'buyer',
            'currency_code' => 'USD',
        ]);

        $cost = $this->costing->sheet([
            ['name' => 'cotton', 'amount' => '4200'],
            ['name' => 'conversion', 'amount' => '1800'],
            ['name' => 'dyeing', 'amount' => '2600'],
            ['name' => 'packing', 'amount' => '350'],
        ], '2500', '12');

        $sheet = CostSheet::query()->create([
            ...$common,
            'number' => $this->numbers->next('cost-sheet', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'item_id' => $ctx['fabric']->id,
            'qty' => '2500',
            'material_cost' => '4200',
            'conversion_cost' => '1800',
            'packing_cost' => '350',
            'overhead_cost' => '2600',
            'total_cost' => $cost['total'],
            'margin_percent' => '12',
            'quote_rate' => $cost['rate'],
            'currency_code' => 'USD',
            'total_amount' => $cost['quote_value'],
        ]);
        foreach ($cost['elements'] as $element) {
            CostSheetLine::query()->create([
                'company_id' => $company->id,
                'cost_sheet_id' => $sheet->id,
                'element' => $element['name'],
                'standard_amount' => $element['amount'],
                'actual_amount' => $element['amount'],
            ]);
        }

        $quotation = Quotation::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'inquiry_id' => $inquiry->id,
            'number' => $this->numbers->next('quotation', $company->id, $branchId),
            'status' => 'draft',
            'document_date' => now()->toDateString(),
            'currency_code' => 'USD',
            'valid_until' => now()->addDays(15)->toDateString(),
            'margin_percent' => '12',
            'total_amount' => $cost['quote_value'],
        ]);
        QuotationLine::query()->create([
            'company_id' => $company->id,
            'quotation_id' => $quotation->id,
            'item_id' => $ctx['fabric']->id,
            'description' => $ctx['fabric']->construction,
            'qty' => '10000',
            'rate' => $cost['rate'],
            'amount' => Decimal::mul('10000', $cost['rate'], 4),
        ]);
        $approval = $this->approvals->submit($quotation, $ctx['user']);
        $this->approvals->act($approval, $ctx['user'], 'approve', 'Buyer accepted the price.');

        $order = SalesOrder::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'quotation_id' => $quotation->id,
            'number' => $this->numbers->next('sales-order', $company->id, $branchId),
            'status' => 'draft',
            'document_date' => now()->toDateString(),
            'delivery_date' => now()->addDays(45)->toDateString(),
            'currency_code' => 'USD',
            'total_amount' => Decimal::mul('10000', $cost['rate'], 4),
        ]);
        SalesOrderLine::query()->create([
            'company_id' => $company->id,
            'sales_order_id' => $order->id,
            'item_id' => $ctx['fabric']->id,
            'description' => 'Dyed poplin',
            'qty' => '10000',
            'rate' => $cost['rate'],
            'amount' => $order->total_amount,
        ]);
        $this->approvals->submit($order, $ctx['user']);

        $plan = ProductionPlan::query()->create([
            ...$common,
            'number' => $this->numbers->next('plan', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'sales_order_id' => $order->id,
            'item_id' => $ctx['fabric']->id,
            'qty' => '10000',
            'promise_date' => $order->delivery_date,
        ]);
        BomLine::query()->create([
            'company_id' => $company->id,
            'production_plan_id' => $plan->id,
            'parent_item_id' => $ctx['fabric']->id,
            'component_item_id' => $ctx['yarn']->id,
            'qty_per' => '0.22',
            'waste_percent' => '4',
        ]);
        $yarnNeed = Decimal::mul('10000', Decimal::mul('0.22', '1.04', 4), 4);
        MrpRequirement::query()->create([
            ...$common,
            'item_id' => $ctx['cotton']->id,
            'required_qty' => Decimal::mul($yarnNeed, '1.18', 4),
            'available_qty' => '0',
            'shortage_qty' => Decimal::mul($yarnNeed, '1.18', 4),
            'needed_by' => now()->addDays(7)->toDateString(),
        ]);

        $pr = PurchaseRequisition::query()->create([
            ...$common,
            'number' => $this->numbers->next('pr', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'needed_by' => now()->addDays(7)->toDateString(),
        ]);
        PurchaseRequisitionLine::query()->create([
            'company_id' => $company->id,
            'purchase_requisition_id' => $pr->id,
            'item_id' => $ctx['cotton']->id,
            'qty' => Decimal::mul($yarnNeed, '1.18', 4),
            'estimated_rate' => '1.85',
        ]);
        $po = PurchaseOrder::query()->create([
            ...$common,
            'party_id' => $ctx['supplier']->id,
            'number' => $this->numbers->next('po', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'currency_code' => 'USD',
            'total_amount' => Decimal::mul(Decimal::mul($yarnNeed, '1.18', 4), '1.85', 4),
        ]);
        PurchaseOrderLine::query()->create([
            'company_id' => $company->id,
            'purchase_order_id' => $po->id,
            'item_id' => $ctx['cotton']->id,
            'qty' => Decimal::mul($yarnNeed, '1.18', 4),
            'rate' => '1.85',
            'amount' => $po->total_amount,
        ]);

        $receipt = GoodsReceipt::query()->create([
            ...$common,
            'purchase_order_id' => $po->id,
            'warehouse_id' => $ctx['warehouse']->id,
            'party_id' => $ctx['supplier']->id,
            'number' => $this->numbers->next('grn', $company->id, $branchId),
            'status' => 'quality_hold',
            'document_date' => now()->toDateString(),
            'total_amount' => $po->total_amount,
        ]);
        $qty = Decimal::mul($yarnNeed, '1.18', 4);
        $movement = $this->stock->receive($ctx['cotton'], $ctx['warehouse']->id, $qty, '1.850000', 'BALE-2401', GoodsReceipt::class, $receipt->id, 'quality_hold', [
            'micronaire' => '4.2',
            'staple' => '28.5',
            'strength' => '29.0',
            'uniformity' => '82',
            'trash' => '3.1',
            'moisture' => '8.0',
        ]);
        GoodsReceiptLine::query()->create([
            'company_id' => $company->id,
            'goods_receipt_id' => $receipt->id,
            'item_id' => $ctx['cotton']->id,
            'item_lot_id' => $movement->item_lot_id,
            'qty' => $qty,
            'rate' => '1.85',
            'quality_status' => 'hold',
        ]);
        $this->issues->evaluate('cotton.received', $company->id, $branchId, [
            'value' => '1',
            'operator' => 'always',
            'message' => 'Bale lot is on quality hold until the fiber lab report is accepted.',
            'severity' => 'high',
        ], $receipt);
        $lot = $movement->itemLot;
        $lot->status = 'available';
        $adjusted = Decimal::sub('1.850000', '0.020000', 6);
        $lot->unit_cost = $adjusted;
        $lot->save();

        $mixPlan = $this->mixing->suggest([
            ['id' => $lot->id, 'kg' => (string) $lot->qty_on_hand, 'micronaire' => '4.2', 'staple' => '28.5', 'strength' => '29.0'],
        ], ['micronaire' => '4.2', 'staple' => '28.5', 'strength' => '29.0'], '500');
        $mixing = CottonMixing::query()->create([
            ...$common,
            'number' => $this->numbers->next('mixing', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'target_micronaire' => '4.2',
            'target_staple' => '28.5',
            'target_strength' => '29.0',
            'laydown_kg' => '500',
            'notes' => $mixPlan['explanation'],
        ]);
        foreach ($mixPlan['lines'] as $line) {
            CottonMixingLine::query()->create([
                'company_id' => $company->id,
                'cotton_mixing_id' => $mixing->id,
                'item_lot_id' => $line['id'],
                'kg' => $line['kg'],
            ]);
        }
        $this->stock->issue($ctx['cotton'], $ctx['warehouse']->id, '500', $lot->id, CottonMixing::class, $mixing->id, 'mixing');

        $kg = $this->ring->kilograms('16500', '8', '22.5', '30', '0.92', (int) $ctx['ring']->spindle_count);
        $yarnLot = YarnLot::query()->create([
            ...$common,
            'lot_no' => 'YL-30-001',
            'item_id' => $ctx['yarn']->id,
            'count_ne' => '30',
            'tpi' => '22.5',
            'grade' => 'A',
            'qty_kg' => $kg,
            'cotton_mixing_id' => $mixing->id,
            'status' => 'released',
        ]);
        SpinningProduction::query()->create([
            ...$common,
            'number' => $this->numbers->next('spinning', $company->id, $branchId),
            'status' => 'posted',
            'document_date' => now()->toDateString(),
            'machine_id' => $ctx['ring']->id,
            'stage' => 'ring',
            'shift_id' => $ctx['shift']->id,
            'production_date' => now()->toDateString(),
            'input_kg' => '500',
            'output_kg' => $kg,
            'waste_kg' => Decimal::sub('500', Decimal::min($kg, '500'), 4),
            'efficiency' => '92',
            'spindle_rpm' => '16500',
            'tpi' => '22.5',
            'count_ne' => '30',
            'hours' => '8',
            'yarn_lot_id' => $yarnLot->id,
        ]);
        $waste = $this->ring->wastePercent('500', Decimal::min($kg, '500'));
        $this->issues->evaluate('spinning.waste', $company->id, $branchId, [
            'value' => $waste,
            'message' => 'Ring-frame waste is '.$waste.'% against a 2% standard.',
            'severity' => 'medium',
        ], $yarnLot);
        $this->stock->receive($ctx['yarn'], $ctx['warehouse']->id, Decimal::min($kg, '500'), '3.100000', $yarnLot->lot_no, YarnLot::class, $yarnLot->id);

        $meters = $this->weaving->meters('550', '480', '60', '0.86');
        $weaving = WeavingProduction::query()->create([
            ...$common,
            'number' => $this->numbers->next('weaving', $company->id, $branchId),
            'status' => 'posted',
            'document_date' => now()->toDateString(),
            'machine_id' => $ctx['loom']->id,
            'shift_id' => $ctx['shift']->id,
            'production_date' => now()->toDateString(),
            'rpm' => '550',
            'ppi' => '60',
            'minutes' => '480',
            'efficiency' => '86',
            'meters' => $meters,
        ]);
        $points = $this->inspection->pointsPerHundredSquareYards('12', $meters, '63');
        $roll = FabricRoll::query()->create([
            ...$common,
            'roll_no' => 'R-1001',
            'item_id' => $ctx['fabric']->id,
            'length_meter' => $meters,
            'width_inch' => '63',
            'gsm' => '120',
            'grade' => $this->inspection->grade($points),
            'points' => '12',
            'points_per_100' => $points,
            'weaving_production_id' => $weaving->id,
        ]);
        QualityInspection::query()->create([
            ...$common,
            'number' => $this->numbers->next('qc', $company->id, $branchId),
            'status' => 'closed',
            'document_date' => now()->toDateString(),
            'inspection_type' => 'final',
            'item_id' => $ctx['fabric']->id,
            'sample_size' => 80,
            'accept_no' => 5,
            'reject_no' => 6,
            'defects_found' => 2,
            'result' => 'accept',
            'aql' => '2.5',
        ]);

        $recipe = Recipe::query()->create([
            'company_id' => $company->id,
            'code' => 'RD-NAVY-01',
            'name' => 'Navy reactive',
            'version' => 1,
            'liquor_ratio' => '8',
            'dye_class' => 'reactive',
            'locked' => true,
        ]);
        RecipeLine::query()->create([
            'company_id' => $company->id,
            'recipe_id' => $recipe->id,
            'item_id' => $ctx['dye']->id,
            'percent_owf' => '2.5',
            'grams_per_liter' => '0',
        ]);
        $lab = LabDip::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'sales_order_id' => $order->id,
            'number' => $this->numbers->next('lab-dip', $company->id, $branchId),
            'status' => 'approved',
            'document_date' => now()->toDateString(),
            'shade_name' => 'Navy 19-3920',
            'lab_l' => '22',
            'lab_a' => '4',
            'lab_b' => '-18',
            'delta_e' => '0.6',
            'recipe_id' => $recipe->id,
            'buyer_decision' => 'approved',
        ]);
        $scaled = $this->dye->scale([['item_id' => $ctx['dye']->id, 'percent_owf' => '2.5', 'grams_per_liter' => '0']], '400', '8');
        $batch = DyeBatch::query()->create([
            ...$common,
            'number' => $this->numbers->next('batch', $company->id, $branchId),
            'status' => 'dyed',
            'document_date' => now()->toDateString(),
            'recipe_id' => $recipe->id,
            'machine_id' => $ctx['jet']->id,
            'item_id' => $ctx['fabric']->id,
            'weight_kg' => '400',
            'liquor_ratio' => '8',
            'shade_name' => 'Navy 19-3920',
            'rft' => true,
            'actual_cost' => '640',
        ]);
        DyeBatchChemical::query()->create([
            'company_id' => $company->id,
            'dye_batch_id' => $batch->id,
            'item_id' => $ctx['dye']->id,
            'standard_qty' => $scaled['lines'][0]['kg'],
            'actual_qty' => $scaled['lines'][0]['kg'],
        ]);
        $this->stock->receive($ctx['dye'], $ctx['warehouse']->id, '50', '6.500000', 'DYE-N1', null, null);
        $this->stock->issue($ctx['dye'], $ctx['warehouse']->id, $scaled['lines'][0]['kg'], null, DyeBatch::class, $batch->id, 'recipe');
        $finish = FinishingRun::query()->create([
            ...$common,
            'number' => $this->numbers->next('finish', $company->id, $branchId),
            'status' => 'released',
            'document_date' => now()->toDateString(),
            'machine_id' => $ctx['jet']->id,
            'item_id' => $ctx['fabric']->id,
            'process' => 'stenter',
            'temperature_c' => '160',
            'speed' => '40',
            'width_inch' => '58',
            'gsm' => '125',
            'shrinkage_warp' => '2.1',
            'shrinkage_weft' => '1.4',
            'released' => true,
        ]);

        $shipment = Shipment::query()->create([
            ...$common,
            'number' => $this->numbers->next('shipment', $company->id, $branchId),
            'status' => 'dispatched',
            'document_date' => now()->toDateString(),
            'sales_order_id' => $order->id,
            'vehicle_no' => 'DHAKA-METRO-TA-11-2345',
            'total_amount' => $order->total_amount,
        ]);
        GatePass::query()->create([
            ...$common,
            'number' => $this->numbers->next('gate', $company->id, $branchId),
            'status' => 'out',
            'document_date' => now()->toDateString(),
            'vehicle_no' => 'DHAKA-METRO-TA-11-2345',
            'party_id' => $ctx['buyer']->id,
            'pass_type' => 'non_returnable',
            'doc_weight' => '980',
            'direction' => 'out',
        ]);
        $invoice = SalesInvoice::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'sales_order_id' => $order->id,
            'shipment_id' => $shipment->id,
            'number' => $this->numbers->next('invoice', $company->id, $branchId),
            'status' => 'posted',
            'document_date' => now()->toDateString(),
            'currency_code' => 'USD',
            'due_date' => now()->addDays(30)->toDateString(),
            'total_amount' => $order->total_amount,
            'vat_amount' => '0',
            'grand_total' => $order->total_amount,
        ]);
        SalesInvoiceLine::query()->create([
            'company_id' => $company->id,
            'sales_invoice_id' => $invoice->id,
            'item_id' => $ctx['fabric']->id,
            'description' => 'Dyed poplin',
            'qty' => '10000',
            'rate' => $cost['rate'],
            'amount' => $order->total_amount,
        ]);
        $this->posting->postEvent($company->id, $branchId, 'sales.invoice', (string) $invoice->grand_total, 'Export invoice '.$invoice->number, $invoice);

        $lc = LetterOfCredit::query()->create([
            ...$common,
            'party_id' => $ctx['buyer']->id,
            'number' => $this->numbers->next('lc', $company->id, $branchId),
            'status' => 'open',
            'document_date' => now()->toDateString(),
            'lc_type' => 'export',
            'amount' => $invoice->grand_total,
            'currency_code' => 'USD',
            'expiry_date' => now()->addDays(60)->toDateString(),
        ]);
        ExportRealization::query()->create([
            ...$common,
            'number' => $this->numbers->next('brc', $company->id, $branchId),
            'status' => 'realized',
            'document_date' => now()->toDateString(),
            'sales_invoice_id' => $invoice->id,
            'realized_amount' => $invoice->grand_total,
            'brc_no' => 'BRC-24001',
            'realized_on' => now()->toDateString(),
            'total_amount' => $invoice->grand_total,
        ]);
        $this->posting->postEvent($company->id, $branchId, 'sales.receipt', (string) $invoice->grand_total, 'Export proceeds '.$lc->number, $invoice);

        $ot = $this->overtime->settle((string) $ctx['employee']->basic_salary, 540, 2400);
        $payroll = PayrollRun::query()->create([
            ...$common,
            'number' => $this->numbers->next('payroll', $company->id, $branchId),
            'status' => 'draft',
            'document_date' => now()->toDateString(),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
        ]);
        PayrollLine::query()->create([
            'company_id' => $company->id,
            'payroll_run_id' => $payroll->id,
            'employee_id' => $ctx['employee']->id,
            'basic' => $ctx['employee']->basic_salary,
            'ot_pay' => $ot['ot_pay'],
            'net' => Decimal::add((string) $ctx['employee']->basic_salary, $ot['ot_pay'], 2),
        ]);

        return [
            'inquiry' => $inquiry->number,
            'quotation' => $quotation->number,
            'sales_order' => $order->number,
            'yarn_lot' => $yarnLot->lot_no,
            'roll' => $roll->roll_no,
            'grade' => $roll->grade,
            'lab_dip' => $lab->number,
            'batch' => $batch->number,
            'invoice' => $invoice->number,
            'finish' => $finish->number,
            'meters' => (string) $meters,
            'ring_kg' => (string) $kg,
        ];
    }
}

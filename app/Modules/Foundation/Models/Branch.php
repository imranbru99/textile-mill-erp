<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends MillModel
{
    protected $table = 'branches';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'unit_type',
        'address',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Department::class, 'branch_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Section::class, 'branch_id');
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\UserRole::class, 'branch_id');
    }

    public function numberSeries(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\NumberSeries::class, 'branch_id');
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalRequest::class, 'branch_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Attachment::class, 'branch_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Shift::class, 'branch_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\Employee::class, 'branch_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Machine::class, 'branch_id');
    }

    public function itemLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\ItemLot::class, 'branch_id');
    }

    public function inquirys(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Inquiry::class, 'branch_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Quotation::class, 'branch_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrder::class, 'branch_id');
    }

    public function purchaseRequisitions(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisition::class, 'branch_id');
    }

    public function requestForQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\RequestForQuote::class, 'branch_id');
    }

    public function vendorQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuote::class, 'branch_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrder::class, 'branch_id');
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceipt::class, 'branch_id');
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\Warehouse::class, 'branch_id');
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockBalance::class, 'branch_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockMovement::class, 'branch_id');
    }

    public function cycleCounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\CycleCount::class, 'branch_id');
    }

    public function gatePass(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\GatePass::class, 'branch_id');
    }

    public function visitorPass(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\VisitorPass::class, 'branch_id');
    }

    public function weighbridgeTickets(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\WeighbridgeTicket::class, 'branch_id');
    }

    public function freightBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\FreightBill::class, 'branch_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\Shipment::class, 'branch_id');
    }

    public function cottonMixings(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\CottonMixing::class, 'branch_id');
    }

    public function spinningProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\SpinningProduction::class, 'branch_id');
    }

    public function yarnLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\YarnLot::class, 'branch_id');
    }

    public function yarnTests(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\YarnTest::class, 'branch_id');
    }

    public function weaverBeams(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeaverBeam::class, 'branch_id');
    }

    public function weavingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeavingProduction::class, 'branch_id');
    }

    public function fabricRolls(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\FabricRoll::class, 'branch_id');
    }

    public function knittingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Knitting\Models\KnittingProduction::class, 'branch_id');
    }

    public function labDips(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\LabDip::class, 'branch_id');
    }

    public function dyeBatchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatch::class, 'branch_id');
    }

    public function finishingRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\FinishingRun::class, 'branch_id');
    }

    public function effluentLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\EffluentLog::class, 'branch_id');
    }

    public function productionPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\ProductionPlan::class, 'branch_id');
    }

    public function mrpRequirements(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\MrpRequirement::class, 'branch_id');
    }

    public function subcontractOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\SubcontractOrder::class, 'branch_id');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\QualityInspection::class, 'branch_id');
    }

    public function ncrs(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\Ncr::class, 'branch_id');
    }

    public function capas(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\Capa::class, 'branch_id');
    }

    public function customerClaims(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\CustomerClaim::class, 'branch_id');
    }

    public function costSheets(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheet::class, 'branch_id');
    }

    public function maintenancePlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenancePlan::class, 'branch_id');
    }

    public function maintenanceWorkOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenanceWorkOrder::class, 'branch_id');
    }

    public function breakdownLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\BreakdownLog::class, 'branch_id');
    }

    public function utilityMeters(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\UtilityMeter::class, 'branch_id');
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\MeterReading::class, 'branch_id');
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\FuelLog::class, 'branch_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\LeaveRequest::class, 'branch_id');
    }

    public function attendanceDays(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AttendanceDay::class, 'branch_id');
    }

    public function payrollRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PayrollRun::class, 'branch_id');
    }

    public function accidentReports(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AccidentReport::class, 'branch_id');
    }

    public function journalEntrys(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalEntry::class, 'branch_id');
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoice::class, 'branch_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Receipt::class, 'branch_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierBill::class, 'branch_id');
    }

    public function supplierPayments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierPayment::class, 'branch_id');
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Cheque::class, 'branch_id');
    }

    public function letterOfCredits(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LetterOfCredit::class, 'branch_id');
    }

    public function exportRealizations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\ExportRealization::class, 'branch_id');
    }

    public function bondedRegisters(): HasMany
    {
        return $this->hasMany(\App\Modules\Compliance\Models\BondedRegister::class, 'branch_id');
    }

    public function sampleRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SampleRequest::class, 'branch_id');
    }

    public function capexRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Projects\Models\CapexRequest::class, 'branch_id');
    }

    public function millIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\MillIssue::class, 'branch_id');
    }

    public function dailySummarys(): HasMany
    {
        return $this->hasMany(\App\Modules\Reports\Models\DailySummary::class, 'branch_id');
    }

}

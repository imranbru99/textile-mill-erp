<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends MillModel
{
    protected $table = 'companies';

    protected bool $companyScoped = false;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'code',
        'name',
        'legal_name',
        'brand_name',
        'country',
        'currency_code',
        'timezone',
        'fiscal_start_month',
        'vat_rate',
        'valuation_method',
        'address',
        'phone',
        'email',
        'website',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'vat_rate' => 'decimal:4',
        ];
    }

    public function branchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Branch::class, 'company_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Department::class, 'company_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Section::class, 'company_id');
    }

    public function costCenters(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\CostCenter::class, 'company_id');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Role::class, 'company_id');
    }

    public function companyModules(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\CompanyModule::class, 'company_id');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Setting::class, 'company_id');
    }

    public function numberSeries(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\NumberSeries::class, 'company_id');
    }

    public function approvalWorkflows(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalWorkflow::class, 'company_id');
    }

    public function approvalLevels(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalLevel::class, 'company_id');
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalRequest::class, 'company_id');
    }

    public function approvalActions(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalAction::class, 'company_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\AuditLog::class, 'company_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Attachment::class, 'company_id');
    }

    public function printTemplates(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\PrintTemplate::class, 'company_id');
    }

    public function currencys(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Currency::class, 'company_id');
    }

    public function exchangeRates(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\ExchangeRate::class, 'company_id');
    }

    public function unitOfMeasures(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\UnitOfMeasure::class, 'company_id');
    }

    public function unitConversions(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\UnitConversion::class, 'company_id');
    }

    public function taxs(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Tax::class, 'company_id');
    }

    public function banks(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Bank::class, 'company_id');
    }

    public function paymentTerms(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\PaymentTerm::class, 'company_id');
    }

    public function incoterms(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Incoterm::class, 'company_id');
    }

    public function ports(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Port::class, 'company_id');
    }

    public function hsCodes(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\HsCode::class, 'company_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Shift::class, 'company_id');
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Holiday::class, 'company_id');
    }

    public function designations(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Designation::class, 'company_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\Employee::class, 'company_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Machine::class, 'company_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Item::class, 'company_id');
    }

    public function itemLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\ItemLot::class, 'company_id');
    }

    public function partys(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Party::class, 'company_id');
    }

    public function partyContacts(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\PartyContact::class, 'company_id');
    }

    public function inquirys(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Inquiry::class, 'company_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Quotation::class, 'company_id');
    }

    public function quotationLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\QuotationLine::class, 'company_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrder::class, 'company_id');
    }

    public function salesOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrderLine::class, 'company_id');
    }

    public function priceLists(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\PriceList::class, 'company_id');
    }

    public function priceListItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\PriceListItem::class, 'company_id');
    }

    public function purchaseRequisitions(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisition::class, 'company_id');
    }

    public function purchaseRequisitionLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisitionLine::class, 'company_id');
    }

    public function requestForQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\RequestForQuote::class, 'company_id');
    }

    public function vendorQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuote::class, 'company_id');
    }

    public function vendorQuoteLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuoteLine::class, 'company_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrder::class, 'company_id');
    }

    public function purchaseOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrderLine::class, 'company_id');
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceipt::class, 'company_id');
    }

    public function goodsReceiptLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceiptLine::class, 'company_id');
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\Warehouse::class, 'company_id');
    }

    public function storageBins(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StorageBin::class, 'company_id');
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockBalance::class, 'company_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockMovement::class, 'company_id');
    }

    public function cycleCounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\CycleCount::class, 'company_id');
    }

    public function gatePass(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\GatePass::class, 'company_id');
    }

    public function visitorPass(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\VisitorPass::class, 'company_id');
    }

    public function weighbridgeTickets(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\WeighbridgeTicket::class, 'company_id');
    }

    public function transporters(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\Transporter::class, 'company_id');
    }

    public function freightBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\FreightBill::class, 'company_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\Shipment::class, 'company_id');
    }

    public function shipmentLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\ShipmentLine::class, 'company_id');
    }

    public function cottonMixings(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\CottonMixing::class, 'company_id');
    }

    public function cottonMixingLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\CottonMixingLine::class, 'company_id');
    }

    public function spinningProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\SpinningProduction::class, 'company_id');
    }

    public function yarnLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\YarnLot::class, 'company_id');
    }

    public function yarnTests(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\YarnTest::class, 'company_id');
    }

    public function weaverBeams(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeaverBeam::class, 'company_id');
    }

    public function weavingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeavingProduction::class, 'company_id');
    }

    public function fabricRolls(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\FabricRoll::class, 'company_id');
    }

    public function fabricDefects(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\FabricDefect::class, 'company_id');
    }

    public function knittingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Knitting\Models\KnittingProduction::class, 'company_id');
    }

    public function labDips(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\LabDip::class, 'company_id');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\Recipe::class, 'company_id');
    }

    public function recipeLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\RecipeLine::class, 'company_id');
    }

    public function dyeBatchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatch::class, 'company_id');
    }

    public function dyeBatchSteps(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatchStep::class, 'company_id');
    }

    public function dyeBatchChemicals(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatchChemical::class, 'company_id');
    }

    public function finishingRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\FinishingRun::class, 'company_id');
    }

    public function effluentLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\EffluentLog::class, 'company_id');
    }

    public function productionPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\ProductionPlan::class, 'company_id');
    }

    public function bomLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\BomLine::class, 'company_id');
    }

    public function mrpRequirements(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\MrpRequirement::class, 'company_id');
    }

    public function subcontractOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\SubcontractOrder::class, 'company_id');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\QualityInspection::class, 'company_id');
    }

    public function ncrs(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\Ncr::class, 'company_id');
    }

    public function capas(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\Capa::class, 'company_id');
    }

    public function customerClaims(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\CustomerClaim::class, 'company_id');
    }

    public function calibrations(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\Calibration::class, 'company_id');
    }

    public function costSheets(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheet::class, 'company_id');
    }

    public function costSheetLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheetLine::class, 'company_id');
    }

    public function cottonPositions(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CottonPosition::class, 'company_id');
    }

    public function maintenancePlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenancePlan::class, 'company_id');
    }

    public function maintenanceWorkOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenanceWorkOrder::class, 'company_id');
    }

    public function breakdownLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\BreakdownLog::class, 'company_id');
    }

    public function conditionReadings(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\ConditionReading::class, 'company_id');
    }

    public function utilityMeters(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\UtilityMeter::class, 'company_id');
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\MeterReading::class, 'company_id');
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Utilities\Models\FuelLog::class, 'company_id');
    }

    public function leaveTypes(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\LeaveType::class, 'company_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\LeaveRequest::class, 'company_id');
    }

    public function attendanceDays(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AttendanceDay::class, 'company_id');
    }

    public function payrollRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PayrollRun::class, 'company_id');
    }

    public function payrollLines(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PayrollLine::class, 'company_id');
    }

    public function employeeLoans(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\EmployeeLoan::class, 'company_id');
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\TrainingRecord::class, 'company_id');
    }

    public function accidentReports(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AccidentReport::class, 'company_id');
    }

    public function ppeIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PpeIssue::class, 'company_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Account::class, 'company_id');
    }

    public function fiscalYears(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\FiscalYear::class, 'company_id');
    }

    public function fiscalPeriods(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\FiscalPeriod::class, 'company_id');
    }

    public function journalEntrys(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalEntry::class, 'company_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalLine::class, 'company_id');
    }

    public function accountMappings(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\AccountMapping::class, 'company_id');
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoice::class, 'company_id');
    }

    public function salesInvoiceLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoiceLine::class, 'company_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Receipt::class, 'company_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierBill::class, 'company_id');
    }

    public function supplierPayments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierPayment::class, 'company_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankAccount::class, 'company_id');
    }

    public function bankStatementLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankStatementLine::class, 'company_id');
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Cheque::class, 'company_id');
    }

    public function letterOfCredits(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LetterOfCredit::class, 'company_id');
    }

    public function lcShipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LcShipment::class, 'company_id');
    }

    public function exportRealizations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\ExportRealization::class, 'company_id');
    }

    public function fixedAssets(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\FixedAsset::class, 'company_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Budget::class, 'company_id');
    }

    public function bondedRegisters(): HasMany
    {
        return $this->hasMany(\App\Modules\Compliance\Models\BondedRegister::class, 'company_id');
    }

    public function sampleRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SampleRequest::class, 'company_id');
    }

    public function capexRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Projects\Models\CapexRequest::class, 'company_id');
    }

    public function vaultDocuments(): HasMany
    {
        return $this->hasMany(\App\Modules\Projects\Models\VaultDocument::class, 'company_id');
    }

    public function millIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\MillIssue::class, 'company_id');
    }

    public function automationRules(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\AutomationRule::class, 'company_id');
    }

    public function automationLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\AutomationLog::class, 'company_id');
    }

    public function knowledgeArticles(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\KnowledgeArticle::class, 'company_id');
    }

    public function cmsPages(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\CmsPage::class, 'company_id');
    }

    public function cmsBlocks(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\CmsBlock::class, 'company_id');
    }

    public function newsPosts(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\NewsPost::class, 'company_id');
    }

    public function jobPostings(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\JobPosting::class, 'company_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\JobApplication::class, 'company_id');
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\ContactMessage::class, 'company_id');
    }

    public function aiInvocations(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\AiInvocation::class, 'company_id');
    }

    public function dailySummarys(): HasMany
    {
        return $this->hasMany(\App\Modules\Reports\Models\DailySummary::class, 'company_id');
    }

    public function reportSchedules(): HasMany
    {
        return $this->hasMany(\App\Modules\Reports\Models\ReportSchedule::class, 'company_id');
    }

}

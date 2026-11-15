#!/usr/bin/env python3
"""Emit mill migrations, models, policies, and Filament resources from a schema."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

# table model Module flags columns
# flags: plain | company | branch | doc | nomodel | noui
# column: name:type[:null][:index][:unique][:default=VALUE]
# types: string text bool int date datetime json qty rate fk:table

SCHEMA = r"""
companies Company Foundation plain code:string:unique;name:string;legal_name:string:null;brand_name:string:null;country:string:default=BD;currency_code:string:default=BDT;timezone:string:default=Asia/Dhaka;fiscal_start_month:int:default=7;vat_rate:qty:default=15;valuation_method:string:default=weighted_average;address:text:null;phone:string:null;email:string:null;website:string:null
branches Branch Foundation company code:string;name:string;unit_type:string;address:text:null;is_active:bool:default=1
departments Department Foundation company,branch code:string;name:string;cost_center_id:fk:cost_centers:null
sections Section Foundation company,branch department_id:fk:departments;name:string;code:string:null
cost_centers CostCenter Foundation company code:string;name:string
roles Role Foundation company name:string;slug:string;is_system:bool:default=0
permissions Permission Foundation plain name:string:unique;module:string;resource:string;action:string
role_permission RolePermission Foundation nomodel,noui,plain role_id:fk:roles;permission_id:fk:permissions
user_role UserRole Foundation nomodel,noui,plain user_id:fk:users;role_id:fk:roles;branch_id:fk:branches:null
company_modules CompanyModule Foundation company module_key:string;enabled:bool:default=1;settings:json:null
settings Setting Foundation company key:string;value:json:null
number_series NumberSeries Foundation company,branch document_type:string;prefix:string;next_number:int:default=1;padding:int:default=5;reset_rule:string:default=yearly
approval_workflows ApprovalWorkflow Foundation company document_type:string;name:string;is_active:bool:default=1
approval_levels ApprovalLevel Foundation company workflow_id:fk:approval_workflows;level:int;role_slug:string;min_amount:qty:default=0;max_amount:qty:null;escalate_hours:int:default=24
approval_requests ApprovalRequest Foundation company,branch,doc document_type:string;document_id:int;current_level:int:default=1;requested_by:fk:users:null
approval_actions ApprovalAction Foundation company request_id:fk:approval_requests;level:int;user_id:fk:users:null;action:string;comment:text:null
audit_logs AuditLog Foundation noui,company auditable_type:string;auditable_id:int;event:string;old_values:json:null;new_values:json:null;ip:string:null;user_agent:text:null;user_id:fk:users:null
attachments Attachment Foundation company,branch attachable_type:string;attachable_id:int;disk:string:default=local;path:string;original_name:string;mime:string:null;size:int:default=0
print_templates PrintTemplate Foundation company document_type:string;name:string;locale:string:default=en;body:text;is_default:bool:default=0
currencies Currency Master company code:string;name:string;symbol:string:null;decimal_places:int:default=2;is_base:bool:default=0
exchange_rates ExchangeRate Master company currency_code:string;rate_date:date;rate:rate;source:string:null
units_of_measure UnitOfMeasure Master company code:string;name:string;dimension:string
unit_conversions UnitConversion Master company from_unit_id:fk:units_of_measure;to_unit_id:fk:units_of_measure;factor:rate
taxes Tax Master company code:string;name:string;rate:qty;kind:string:default=vat
banks Bank Master company name:string;branch_name:string:null;account_no:string:null;routing_no:string:null;swift:string:null
payment_terms PaymentTerm Master company code:string;name:string;days:int:default=0
incoterms Incoterm Master company code:string;name:string
ports Port Master company code:string;name:string;country:string:default=BD
hs_codes HsCode Master company code:string;description:string;unit:string:null
shifts Shift Master company,branch code:string;name:string;starts_at:string;ends_at:string;break_minutes:int:default=0;is_night:bool:default=0
holidays Holiday Master company name:string;holiday_date:date;is_paid:bool:default=1
designations Designation Master company name:string;grade:string:null
employees Employee People company,branch employee_no:string;name:string;designation_id:fk:designations:null;department_id:fk:departments:null;category:string:default=permanent;join_date:date:null;basic_salary:qty:default=0;phone:string:null;nid:string:null;status:string:default=active;device_pin:string:null
machines Machine Master company,branch code:string;name:string;section:string;make:string:null;model:string:null;capacity:qty:null;install_date:date:null;spindle_count:int:null;location:string:null;status:string:default=running;running_hours:qty:default=0
items Item Master company code:string;name:string;slug:string:null;item_type:string;uom_id:fk:units_of_measure:null;count_ne:qty:null;ply:int:null;tpi:qty:null;gsm:qty:null;width_inch:qty:null;epi:qty:null;ppi:qty:null;construction:string:null;weave:string:null;fiber_json:json:null;color:string:null;shade_code:string:null;hazard_class:string:null;shelf_life_days:int:null;reorder_level:qty:default=0;min_qty:qty:default=0;max_qty:qty:default=0;standard_cost:rate:default=0;barcode:string:null
item_lots ItemLot Inventory company,branch item_id:fk:items;warehouse_id:fk:warehouses;lot_no:string;qty_on_hand:qty:default=0;qty_reserved:qty:default=0;unit_cost:rate:default=0;received_at:datetime:null;expiry_date:date:null;status:string:default=available;barcode:string:null;attributes:json:null;supplier_id:fk:parties:null
parties Party Commercial company code:string;name:string;party_type:string;country:string:default=BD;currency_code:string:default=BDT;credit_limit:qty:default=0;rating:qty:default=0;payment_term_id:fk:payment_terms:null;blacklisted:bool:default=0;phone:string:null;email:string:null;address:text:null;bin_vat:string:null
party_contacts PartyContact Commercial company party_id:fk:parties;name:string;email:string:null;phone:string:null;role:string:null
inquiries Inquiry Commercial company,branch,doc party_id:fk:parties:null;subject:string;source:string:default=website;contact_name:string:null;email:string:null;phone:string:null
quotations Quotation Commercial company,branch,doc party_id:fk:parties;inquiry_id:fk:inquiries:null;valid_until:date:null;revision:int:default=1;margin_percent:qty:default=0
quotation_lines QuotationLine Commercial noui,company quotation_id:fk:quotations;item_id:fk:items:null;description:string;qty:qty;rate:rate;amount:qty
sales_orders SalesOrder Commercial company,branch,doc party_id:fk:parties;quotation_id:fk:quotations:null;delivery_date:date:null;incoterm_id:fk:incoterms:null;payment_term_id:fk:payment_terms:null
sales_order_lines SalesOrderLine Commercial noui,company sales_order_id:fk:sales_orders;item_id:fk:items;description:string;qty:qty;rate:rate;amount:qty;uom_id:fk:units_of_measure:null
price_lists PriceList Commercial company name:string;currency_code:string:default=USD;valid_from:date:null;valid_to:date:null
price_list_items PriceListItem Commercial noui,company price_list_id:fk:price_lists;item_id:fk:items;rate:rate
purchase_requisitions PurchaseRequisition Supply company,branch,doc department_id:fk:departments:null;needed_by:date:null
purchase_requisition_lines PurchaseRequisitionLine Supply noui,company purchase_requisition_id:fk:purchase_requisitions;item_id:fk:items;qty:qty;estimated_rate:rate:default=0
request_for_quotes RequestForQuote Supply company,branch,doc purchase_requisition_id:fk:purchase_requisitions:null
vendor_quotes VendorQuote Supply company,branch,doc party_id:fk:parties;request_for_quote_id:fk:request_for_quotes:null;rank:int:null;score:qty:null
vendor_quote_lines VendorQuoteLine Supply noui,company vendor_quote_id:fk:vendor_quotes;item_id:fk:items;qty:qty;rate:rate;lead_days:int:default=0
purchase_orders PurchaseOrder Supply company,branch,doc party_id:fk:parties;vendor_quote_id:fk:vendor_quotes:null;incoterm_id:fk:incoterms:null;payment_term_id:fk:payment_terms:null
purchase_order_lines PurchaseOrderLine Supply noui,company purchase_order_id:fk:purchase_orders;item_id:fk:items;qty:qty;received_qty:qty:default=0;rate:rate;amount:qty
goods_receipts GoodsReceipt Supply company,branch,doc purchase_order_id:fk:purchase_orders:null;warehouse_id:fk:warehouses;party_id:fk:parties:null
goods_receipt_lines GoodsReceiptLine Supply noui,company goods_receipt_id:fk:goods_receipts;item_id:fk:items;item_lot_id:fk:item_lots:null;qty:qty;rate:rate;quality_status:string:default=hold
warehouses Warehouse Inventory company,branch code:string;name:string;kind:string;is_bonded:bool:default=0
storage_bins StorageBin Inventory company warehouse_id:fk:warehouses;code:string;name:string:null
stock_balances StockBalance Inventory noui,company,branch item_id:fk:items;item_lot_id:fk:item_lots:null;warehouse_id:fk:warehouses;qty:qty:default=0;value:qty:default=0
stock_movements StockMovement Inventory company,branch item_id:fk:items;item_lot_id:fk:item_lots:null;warehouse_id:fk:warehouses;direction:string;qty:qty;unit_cost:rate;movement_type:string;reference_type:string:null;reference_id:int:null;balance_after:qty:default=0;transacted_at:datetime:null
cycle_counts CycleCount Inventory company,branch,doc warehouse_id:fk:warehouses;counted_on:date:null
gate_passes GatePass Gate company,branch,doc vehicle_no:string:null;driver_name:string:null;party_id:fk:parties:null;pass_type:string:default=non_returnable;return_due:date:null;doc_weight:qty:null;bridge_weight:qty:null;direction:string:default=out
visitor_passes VisitorPass Gate company,branch visitor_name:string;host_name:string:null;purpose:string:null;in_at:datetime:null;out_at:datetime:null;vehicle_no:string:null
weighbridge_tickets WeighbridgeTicket Gate company,branch,doc vehicle_no:string;gross:qty;tare:qty;net:qty;gate_pass_id:fk:gate_passes:null
transporters Transporter Logistics company name:string;phone:string:null;vehicle_no:string:null
freight_bills FreightBill Logistics company,branch,doc transporter_id:fk:transporters;shipment_id:fk:shipments:null;amount:qty
shipments Shipment Logistics company,branch,doc sales_order_id:fk:sales_orders:null;transporter_id:fk:transporters:null;vehicle_no:string:null;delivered_at:datetime:null;pod_name:string:null
shipment_lines ShipmentLine Logistics noui,company shipment_id:fk:shipments;item_id:fk:items;item_lot_id:fk:item_lots:null;qty:qty;packages:int:default=0
cotton_mixings CottonMixing Spinning company,branch,doc target_micronaire:qty:null;target_staple:qty:null;target_strength:qty:null;laydown_kg:qty
cotton_mixing_lines CottonMixingLine Spinning noui,company cotton_mixing_id:fk:cotton_mixings;item_lot_id:fk:item_lots;kg:qty
spinning_productions SpinningProduction Spinning company,branch,doc machine_id:fk:machines:null;stage:string;shift_id:fk:shifts:null;production_date:date;input_kg:qty:default=0;output_kg:qty:default=0;waste_kg:qty:default=0;efficiency:qty:default=0;spindle_rpm:qty:null;tpi:qty:null;count_ne:qty:null;hours:qty:default=8;yarn_lot_id:fk:yarn_lots:null
yarn_lots YarnLot Spinning company,branch lot_no:string;item_id:fk:items;count_ne:qty;ply:int:default=1;tpi:qty:null;grade:string:default=A;qty_kg:qty:default=0;cotton_mixing_id:fk:cotton_mixings:null;status:string:default=released
yarn_tests YarnTest Quality company,branch yarn_lot_id:fk:yarn_lots;uster_u:qty:null;ipi:qty:null;hairiness:qty:null;strength:qty:null;cv_percent:qty:null;grade:string:null
weaver_beams WeaverBeam Weaving company,branch beam_no:string;item_id:fk:items:null;ends:int;length_meter:qty;status:string:default=ready;size_pickup:qty:null
weaving_productions WeavingProduction Weaving company,branch,doc machine_id:fk:machines;shift_id:fk:shifts:null;production_date:date;rpm:qty;ppi:qty;minutes:qty;efficiency:qty;meters:qty;warp_stops:int:default=0;weft_stops:int:default=0;mechanical_stops:int:default=0
fabric_rolls FabricRoll Weaving company,branch roll_no:string;item_id:fk:items;length_meter:qty;width_inch:qty;gsm:qty:null;grade:string:default=A;points:qty:default=0;points_per_100:qty:default=0;status:string:default=inspected;weaving_production_id:fk:weaving_productions:null
fabric_defects FabricDefect Quality company fabric_roll_id:fk:fabric_rolls;defect_code:string;points:int;position_meter:qty:null
knitting_productions KnittingProduction Knitting company,branch,doc machine_id:fk:machines;production_date:date;gsm:qty:null;stitch_length:qty:null;kg:qty;rolls:int:default=0
lab_dips LabDip Dyeing company,branch,doc party_id:fk:parties:null;sales_order_id:fk:sales_orders:null;shade_name:string;lab_l:qty:null;lab_a:qty:null;lab_b:qty:null;delta_e:qty:null;recipe_id:fk:recipes:null;buyer_decision:string:null
recipes Recipe Dyeing company code:string;name:string;version:int:default=1;liquor_ratio:qty:default=8;dye_class:string:null;locked:bool:default=0;parent_id:fk:recipes:null
recipe_lines RecipeLine Dyeing noui,company recipe_id:fk:recipes;item_id:fk:items;percent_owf:qty:default=0;grams_per_liter:qty:default=0
dye_batches DyeBatch Dyeing company,branch,doc recipe_id:fk:recipes:null;machine_id:fk:machines:null;item_id:fk:items:null;weight_kg:qty;liquor_ratio:qty;shade_name:string:null;is_redye:bool:default=0;rft:bool:default=1;standard_cost:qty:default=0;actual_cost:qty:default=0
dye_batch_steps DyeBatchStep Dyeing noui,company dye_batch_id:fk:dye_batches;step_no:int;name:string;temperature_c:qty:null;hold_minutes:int:null;ph:qty:null;confirmed_at:datetime:null
dye_batch_chemicals DyeBatchChemical Dyeing noui,company dye_batch_id:fk:dye_batches;item_id:fk:items;standard_qty:qty;actual_qty:qty:default=0
finishing_runs FinishingRun Dyeing company,branch,doc machine_id:fk:machines:null;item_id:fk:items:null;process:string;temperature_c:qty:null;speed:qty:null;overfeed:qty:null;width_inch:qty:null;gsm:qty:null;shrinkage_warp:qty:null;shrinkage_weft:qty:null;released:bool:default=0
effluent_logs EffluentLog Dyeing company,branch log_date:date;ph:qty:null;cod:qty:null;bod:qty:null;tds:qty:null;color:string:null;discharged:bool:default=0
production_plans ProductionPlan Planning company,branch,doc sales_order_id:fk:sales_orders:null;item_id:fk:items:null;qty:qty;start_date:date:null;end_date:date:null;promise_date:date:null
bom_lines BomLine Planning noui,company production_plan_id:fk:production_plans:null;parent_item_id:fk:items;component_item_id:fk:items;qty_per:qty;waste_percent:qty:default=0
mrp_requirements MrpRequirement Planning company,branch item_id:fk:items;required_qty:qty;available_qty:qty:default=0;shortage_qty:qty:default=0;needed_by:date:null;purchase_requisition_id:fk:purchase_requisitions:null
subcontract_orders SubcontractOrder Planning company,branch,doc party_id:fk:parties;process:string;qty:qty;rate:rate
quality_inspections QualityInspection Quality company,branch,doc inspection_type:string;item_id:fk:items:null;item_lot_id:fk:item_lots:null;sample_size:int:null;accept_no:int:null;reject_no:int:null;defects_found:int:default=0;result:string:null;aql:qty:null
ncrs Ncr Quality company,branch,doc source_type:string:null;source_id:int:null;description:text;root_cause:text:null;severity:string:default=medium
capas Capa Quality company,branch ncr_id:fk:ncrs;action:text;owner_id:fk:users:null;due_date:date:null;verified_at:datetime:null;status:string:default=open
customer_claims CustomerClaim Quality company,branch,doc party_id:fk:parties;sales_order_id:fk:sales_orders:null;item_lot_id:fk:item_lots:null;qty:qty;claim_amount:qty:default=0;resolution:string:null
calibrations Calibration Quality company instrument:string;last_done:date:null;next_due:date;certificate_no:string:null
cost_sheets CostSheet Costing company,branch,doc sales_order_id:fk:sales_orders:null;item_id:fk:items:null;qty:qty:default=1;material_cost:qty:default=0;conversion_cost:qty:default=0;packing_cost:qty:default=0;overhead_cost:qty:default=0;total_cost:qty:default=0;margin_percent:qty:default=0;quote_rate:rate:default=0
cost_sheet_lines CostSheetLine Costing noui,company cost_sheet_id:fk:cost_sheets;element:string;standard_amount:qty:default=0;actual_amount:qty:default=0
cotton_positions CottonPosition Costing company position_date:date;open_purchase_kg:qty:default=0;open_sales_kg:qty:default=0;avg_purchase_rate:rate:default=0;avg_sales_rate:rate:default=0;forward_cover:string:null
maintenance_plans MaintenancePlan Maintenance company,branch machine_id:fk:machines;name:string;interval_hours:qty:null;interval_days:int:null;last_done_at:datetime:null;next_due_at:datetime:null
maintenance_work_orders MaintenanceWorkOrder Maintenance company,branch,doc machine_id:fk:machines;breakdown_log_id:fk:breakdown_logs:null;kind:string:default=breakdown;started_at:datetime:null;finished_at:datetime:null;downtime_minutes:int:default=0
breakdown_logs BreakdownLog Maintenance company,branch machine_id:fk:machines;reason_code:string;started_at:datetime;ended_at:datetime:null;minutes:int:default=0
condition_readings ConditionReading Maintenance company machine_id:fk:machines;reading_at:datetime;temperature_c:qty:null;vibration:qty:null
utility_meters UtilityMeter Utilities company,branch code:string;name:string;utility:string;uom:string
meter_readings MeterReading Utilities company,branch utility_meter_id:fk:utility_meters;reading_at:datetime;reading:qty;consumption:qty:default=0;cost:qty:default=0
fuel_logs FuelLog Utilities company,branch log_date:date;liters:qty;amount:qty;generator:string:null
leave_types LeaveType People company code:string;name:string;days_per_year:qty:default=0;paid:bool:default=1
leave_requests LeaveRequest People company,branch employee_id:fk:employees;leave_type_id:fk:leave_types;start_date:date;end_date:date;days:qty;status:string:default=submitted
attendance_days AttendanceDay People company,branch employee_id:fk:employees;work_date:date;shift_id:fk:shifts:null;in_at:datetime:null;out_at:datetime:null;worked_minutes:int:default=0;late_minutes:int:default=0;ot_minutes:int:default=0;status:string:default=present
payroll_runs PayrollRun People company,branch,doc period_start:date;period_end:date
payroll_lines PayrollLine People noui,company payroll_run_id:fk:payroll_runs;employee_id:fk:employees;basic:qty:default=0;ot_pay:qty:default=0;incentive:qty:default=0;bonus:qty:default=0;deduction:qty:default=0;net:qty:default=0
employee_loans EmployeeLoan People company employee_id:fk:employees;principal:qty;balance:qty;installment:qty
training_records TrainingRecord People company employee_id:fk:employees;topic:string;trained_on:date;expires_on:date:null
accident_reports AccidentReport People company,branch,doc employee_id:fk:employees:null;occurred_at:datetime;description:text;severity:string:default=low
ppe_issues PpeIssue People company employee_id:fk:employees;item_id:fk:items:null;qty:qty;issued_on:date
accounts Account Finance company code:string;name:string;account_type:string;parent_id:fk:accounts:null;is_postable:bool:default=1
fiscal_years FiscalYear Finance company name:string;starts_on:date;ends_on:date;is_closed:bool:default=0
fiscal_periods FiscalPeriod Finance company fiscal_year_id:fk:fiscal_years;name:string;starts_on:date;ends_on:date;is_locked:bool:default=0
journal_entries JournalEntry Finance company,branch,doc fiscal_period_id:fk:fiscal_periods:null;source_type:string:null;source_id:int:null;posted_at:datetime:null
journal_lines JournalLine Finance noui,company journal_entry_id:fk:journal_entries;account_id:fk:accounts;debit:qty:default=0;credit:qty:default=0;narration:string:null;cost_center_id:fk:cost_centers:null
account_mappings AccountMapping Finance company event_key:string;debit_account_id:fk:accounts;credit_account_id:fk:accounts
sales_invoices SalesInvoice Finance company,branch,doc party_id:fk:parties;sales_order_id:fk:sales_orders:null;shipment_id:fk:shipments:null;due_date:date:null;vat_amount:qty:default=0;grand_total:qty:default=0
sales_invoice_lines SalesInvoiceLine Finance noui,company sales_invoice_id:fk:sales_invoices;item_id:fk:items:null;description:string;qty:qty;rate:rate;amount:qty
receipts Receipt Finance company,branch,doc party_id:fk:parties;bank_account_id:fk:bank_accounts:null;method:string:default=bank
supplier_bills SupplierBill Finance company,branch,doc party_id:fk:parties;purchase_order_id:fk:purchase_orders:null;due_date:date:null
supplier_payments SupplierPayment Finance company,branch,doc party_id:fk:parties;supplier_bill_id:fk:supplier_bills:null;bank_account_id:fk:bank_accounts:null
bank_accounts BankAccount Finance company bank_id:fk:banks:null;name:string;account_no:string;currency_code:string:default=BDT;gl_account_id:fk:accounts:null
bank_statement_lines BankStatementLine Finance company bank_account_id:fk:bank_accounts;txn_date:date;description:string:null;amount:qty;matched:bool:default=0;journal_entry_id:fk:journal_entries:null
cheques Cheque Finance company,branch,doc bank_account_id:fk:bank_accounts:null;party_id:fk:parties:null;cheque_no:string;cheque_date:date;amount:qty;kind:string:default=received
letters_of_credit LetterOfCredit Finance company,branch,doc party_id:fk:parties:null;lc_type:string;bank_id:fk:banks:null;amount:qty;currency_code:string:default=USD;expiry_date:date:null;margin_percent:qty:default=0
lc_shipments LcShipment Finance company letter_of_credit_id:fk:letters_of_credit;bl_no:string:null;shipped_on:date:null;amount:qty;acceptance_due:date:null
export_realizations ExportRealization Finance company,branch,doc sales_invoice_id:fk:sales_invoices:null;bank_id:fk:banks:null;realized_amount:qty;brc_no:string:null;realized_on:date:null
fixed_assets FixedAsset Finance company code:string;name:string;acquired_on:date;cost:qty;salvage:qty:default=0;life_months:int:default=120;accumulated:qty:default=0
budgets Budget Finance company fiscal_year_id:fk:fiscal_years;account_id:fk:accounts;cost_center_id:fk:cost_centers:null;amount:qty
bonded_registers BondedRegister Compliance company,branch item_id:fk:items;import_qty:qty:default=0;consumed_qty:qty:default=0;exported_qty:qty:default=0;allowed_waste_percent:qty:default=0;period:string
sample_requests SampleRequest Commercial company,branch,doc party_id:fk:parties:null;sample_type:string;item_id:fk:items:null;qty:qty:default=1;feedback:text:null;converted:bool:default=0
capex_requests CapexRequest Projects company,branch,doc title:string;amount:qty;justification:text:null
vault_documents VaultDocument Projects company title:string;category:string;reference_no:string:null;issued_on:date:null;expires_on:date:null;path:string:null
mill_issues MillIssue Issues company,branch,doc issue_type:string;severity:string:default=medium;source_type:string:null;source_id:int:null;owner_id:fk:users:null;due_at:datetime:null;sla_hours:int:default=24;recommendation:text:null;resolution:text:null;knowledge_article_id:fk:knowledge_articles:null
automation_rules AutomationRule Issues company module_key:string;name:string;trigger:string;conditions:json;actions:json;threshold:qty:null;is_active:bool:default=1;auto_execute:bool:default=0
automation_logs AutomationLog Issues noui,company automation_rule_id:fk:automation_rules:null;mill_issue_id:fk:mill_issues:null;action:string;payload:json:null;undone_at:datetime:null
knowledge_articles KnowledgeArticle Issues company title:string;slug:string:null;problem:text;solution:text;module_key:string:null
cms_pages CmsPage Website company title:string;slug:string;locale:string:default=en;excerpt:text:null;body:text;seo_title:string:null;seo_description:string:null;published:bool:default=1
cms_blocks CmsBlock Website company cms_page_id:fk:cms_pages:null;key:string;locale:string:default=en;content:json
news_posts NewsPost Website company title:string;slug:string;locale:string:default=en;body:text;published_at:datetime:null
job_postings JobPosting Website company title:string;slug:string;department:string:null;location:string:null;body:text;closes_on:date:null;is_open:bool:default=1
job_applications JobApplication Website company job_posting_id:fk:job_postings;applicant_name:string;email:string;phone:string:null;status:string:default=applied
contact_messages ContactMessage Website company name:string;email:string;phone:string:null;message:text;inquiry_id:fk:inquiries:null
ai_invocations AiInvocation Issues noui,company feature:string;prompt:text;response:text:null;cost_usd:rate:default=0;user_id:fk:users:null;confirmed:bool:default=0
daily_summaries DailySummary Reports company,branch summary_date:date;section:string;output_qty:qty:default=0;efficiency:qty:default=0;waste_percent:qty:default=0;payload:json:null
report_schedules ReportSchedule Reports company name:string;report_key:string;cron:string:default=0 7 * * *;recipients:string;format:string:default=pdf;is_active:bool:default=1
""".strip().splitlines()

OPTIONS = {
    "status": "draft,submitted,approved,rejected,posted,cancelled,closed,released,hold",
    "item_type": "raw_material,yarn,greige,finished_fabric,dye,chemical,auxiliary,packing,spare,consumable,fuel,wip,scrap",
    "party_type": "buyer,supplier,both",
    "direction": "in,out",
    "severity": "low,medium,high,critical",
    "valuation_method": "fifo,weighted_average",
    "unit_type": "spinning,weaving,knitting,dyeing,head_office,warehouse,depot",
    "kind": "vat,ait,tds,sd,raw_cotton,yarn,greige,finished,dye_chemical,spare,scrap,bonded,preventive,breakdown,received,issued",
    "section": "blowroom,carding,drawing,combing,roving,ring,winding,packing,warping,sizing,loom,knitting,dyeing,finishing,utility",
    "stage": "blowroom,carding,drawing,combing,roving,ring,winding,packing",
    "category": "permanent,contract,piece_rate,daily,agency,license,test,safety,commercial",
    "result": "accept,reject,hold",
    "pass_type": "returnable,non_returnable",
    "lc_type": "export,btb,import",
    "utility": "power,gas,water,steam,air",
    "account_type": "asset,liability,equity,income,expense",
    "inspection_type": "incoming,in_process,final",
    "sample_type": "lab_dip,strike_off,handloom,yarn_dev",
    "buyer_decision": "pending,approved,rejected",
    "quality_status": "hold,released,rejected",
    "method": "bank,cash,bkash,nagad,cheque",
    "process": "singeing,desizing,scouring,bleaching,mercerizing,dyeing,printing,stenter,compacting,calender,sanfor,peach",
    "grade": "A,B,C,reject",
    "reset_rule": "never,yearly,monthly",
    "action": "approve,reject,delegate,notify,create_pr,hold_lot,block",
}

GROUPS = {
    "Foundation": "Organization",
    "Master": "Master Data",
    "Commercial": "Commercial",
    "Supply": "Supply Chain",
    "Inventory": "Inventory",
    "Gate": "Gate & Security",
    "Logistics": "Logistics",
    "Spinning": "Spinning",
    "Weaving": "Weaving",
    "Knitting": "Knitting",
    "Dyeing": "Wet Processing",
    "Planning": "Planning",
    "Quality": "Quality",
    "Costing": "Costing",
    "Maintenance": "Maintenance",
    "Utilities": "Utilities",
    "People": "HR & Payroll",
    "Finance": "Finance",
    "Compliance": "Compliance",
    "Projects": "Projects",
    "Issues": "Issues & Automation",
    "Website": "Website",
    "Reports": "Reports",
}

ICONS = {
    "Foundation": "OutlinedBuildingOffice2",
    "Master": "OutlinedCube",
    "Commercial": "OutlinedUsers",
    "Supply": "OutlinedTruck",
    "Inventory": "OutlinedArchiveBox",
    "Gate": "OutlinedShieldCheck",
    "Logistics": "OutlinedTruck",
    "Spinning": "OutlinedCog6Tooth",
    "Weaving": "OutlinedSquare3Stack3d",
    "Knitting": "OutlinedCircleStack",
    "Dyeing": "OutlinedBeaker",
    "Planning": "OutlinedCalendarDays",
    "Quality": "OutlinedCheckBadge",
    "Costing": "OutlinedCalculator",
    "Maintenance": "OutlinedWrenchScrewdriver",
    "Utilities": "OutlinedBolt",
    "People": "OutlinedIdentification",
    "Finance": "OutlinedBanknotes",
    "Compliance": "OutlinedScale",
    "Projects": "OutlinedFolder",
    "Issues": "OutlinedExclamationTriangle",
    "Website": "OutlinedGlobeAlt",
    "Reports": "OutlinedChartBar",
}

MODULE_KEYS = {
    "Foundation": "foundation",
    "Master": "master",
    "Commercial": "crm",
    "Supply": "purchase",
    "Inventory": "inventory",
    "Gate": "gate",
    "Logistics": "logistics",
    "Spinning": "spinning",
    "Weaving": "weaving",
    "Knitting": "knitting",
    "Dyeing": "dyeing",
    "Planning": "planning",
    "Quality": "quality",
    "Costing": "costing",
    "Maintenance": "maintenance",
    "Utilities": "utilities",
    "People": "hr",
    "Finance": "finance",
    "Compliance": "bonded",
    "Projects": "projects",
    "Issues": "issues",
    "Website": "website",
    "Reports": "reports",
}

MODULE_DEPS = {
    "dyeing": ["inventory", "quality"],
    "spinning": ["inventory"],
    "weaving": ["inventory"],
    "knitting": ["inventory"],
    "planning": ["inventory"],
    "costing": ["finance"],
    "bonded": ["inventory", "finance"],
    "purchase": ["inventory"],
    "gate": ["inventory"],
}


def studly(name: str) -> str:
    return "".join(p.capitalize() for p in re.split(r"[_\s]+", name) if p)


def camel(name: str) -> str:
    if "_" in name or " " in name:
        s = studly(name)
        return s[0].lower() + s[1:] if s else s
    return name[0].lower() + name[1:] if name else name


def plural_method(model: str) -> str:
    # ItemLot -> itemLots
    return camel(model) + "s" if not model.endswith("s") else camel(model)


def parse_column(token: str) -> dict:
    parts = token.split(":")
    name, typ = parts[0], parts[1]
    meta = {"name": name, "type": typ, "null": False, "index": False, "unique": False, "default": None, "ref": None}
    if typ == "fk":
        meta["ref"] = parts[2]
        rest = parts[3:]
    else:
        rest = parts[2:]
    for flag in rest:
        if flag == "null":
            meta["null"] = True
        elif flag == "index":
            meta["index"] = True
        elif flag == "unique":
            meta["unique"] = True
        elif flag.startswith("default="):
            meta["default"] = flag.split("=", 1)[1]
    if name.endswith("_id"):
        meta["index"] = True
    return meta


def parse_schema():
    tables = []
    for line in SCHEMA:
        line = line.strip()
        if not line or line.startswith("#"):
            continue
        bits = line.split()
        table, model, module, flags = bits[0], bits[1], bits[2], bits[3]
        colraw = bits[4] if len(bits) > 4 else ""
        flagset = set(flags.split(","))
        columns = [parse_column(c) for c in colraw.split(";") if c]
        tables.append({
            "table": table,
            "model": model,
            "module": module,
            "flags": flagset,
            "columns": columns,
        })
    return tables


def php_string(value: str) -> str:
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"


def emit_column_php(col: dict) -> str:
    name = col["name"]
    t = col["type"]
    if t == "fk":
        expr = f"$table->unsignedBigInteger('{name}')"
    elif t == "string":
        expr = f"$table->string('{name}')"
    elif t == "text":
        expr = f"$table->text('{name}')"
    elif t == "bool":
        expr = f"$table->boolean('{name}')"
    elif t == "int":
        expr = f"$table->integer('{name}')"
    elif t == "date":
        expr = f"$table->date('{name}')"
    elif t == "datetime":
        expr = f"$table->dateTime('{name}')"
    elif t == "json":
        expr = f"$table->json('{name}')"
    elif t == "qty":
        expr = f"$table->decimal('{name}', 18, 4)"
    elif t == "rate":
        expr = f"$table->decimal('{name}', 18, 6)"
    else:
        raise SystemExit(f"unknown type {t}")
    if col["null"] or (t == "fk" and col["null"]):
        expr += "->nullable()"
    if col["default"] is not None:
        d = col["default"]
        if t == "bool":
            expr += f"->default({1 if d in ('1', 'true') else 0})"
        elif t in ("int",):
            expr += f"->default({int(d)})"
        elif t in ("qty", "rate"):
            expr += f"->default({d})"
        else:
            expr += f"->default({php_string(d)})"
    if col["unique"]:
        expr += "->unique()"
    elif (col["index"] or t == "fk") and name not in ("company_id", "branch_id"):
        expr += "->index()"
    return expr + ";"


DOC_COLUMNS = [
    parse_column(c)
    for c in [
        "number:string:index",
        "status:string:default=draft:index",
        "document_date:date:null",
        "currency_code:string:default=BDT",
        "exchange_rate:rate:default=1",
        "notes:text:null",
        "total_amount:qty:default=0",
        "qr_token:string:null",
    ]
]


def full_columns(spec: dict) -> list:
    cols = []
    flags = spec["flags"]
    if "company" in flags:
        cols.append(parse_column("company_id:fk:companies"))
    if "branch" in flags:
        cols.append(parse_column("branch_id:fk:branches:null"))
    cols.extend(spec["columns"])
    if "doc" in flags:
        names = {c["name"] for c in cols}
        for extra in DOC_COLUMNS:
            if extra["name"] not in names:
                cols.append(extra)
    return cols


def write(path: Path, content: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(content, encoding="utf-8")


def ordered_tables(tables: list) -> list:
    by = {spec["table"]: spec for spec in tables}
    remaining = {spec["table"]: spec for spec in tables}
    done: list = []
    created: set[str] = set()
    while remaining:
        ready = []
        for name, spec in remaining.items():
            refs = {
                col["ref"]
                for col in full_columns(spec)
                if col["type"] == "fk" and col["ref"] in by and col["ref"] != name
            }
            if refs <= created:
                ready.append(name)
        if not ready:
            ready = [next(iter(remaining))]
        for name in ready:
            done.append(remaining.pop(name))
            created.add(name)
    return done


def migration(tables: list) -> str:
    tables = ordered_tables(tables)
    chunks = []
    deferred = []
    created: set[str] = {"users"}
    for spec in tables:
        lines = ["        $table->id();", "        $table->ulid('public_id')->unique();"]
        for col in full_columns(spec):
            if col["type"] == "fk" and col["ref"] in created and col["ref"] != spec["table"]:
                action = "nullOnDelete" if col["null"] else "restrictOnDelete"
                nullable = "->nullable()" if col["null"] else ""
                lines.append(
                    f"        $table->foreignId('{col['name']}'){nullable}->constrained('{col['ref']}')->{action}();"
                )
            else:
                lines.append("        " + emit_column_php(col))
                if col["type"] == "fk":
                    action = "nullOnDelete" if col["null"] else "restrictOnDelete"
                    deferred.append((spec["table"], col["name"], col["ref"], action))
        lines.append("        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();")
        lines.append("        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();")
        lines.append("        $table->unsignedInteger('lock_version')->default(0);")
        lines.append("        $table->timestamps();")
        lines.append("        $table->softDeletes();")
        if "company" in spec["flags"] and "branch" in spec["flags"]:
            lines.append("        $table->index(['company_id', 'branch_id']);")
        chunks.append(
            "        Schema::create('%s', function (Blueprint $table) {\n%s\n        });" % (spec["table"], "\n".join(lines))
        )
        created.add(spec["table"])
    fk_lines = [
        "        Schema::table('%s', function (Blueprint $table) {\n            $table->foreign('%s')->references('id')->on('%s')->%s();\n        });"
        % item
        for item in deferred
    ]
    user_alter = """
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('locale', 5)->default('en');
            $table->string('user_type')->default('staff')->index();
            $table->boolean('is_active')->default(true);
            $table->text('two_factor_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->softDeletes();
        });
"""
    # companies and branches must exist before user alter. They are first in schema.
    down_tables = "\n".join(
        "        Schema::dropIfExists('%s');" % spec["table"] for spec in reversed(tables)
    )
    return f"""<?php

declare(strict_types=1);

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{{
    public function up(): void
    {{
        Schema::disableForeignKeyConstraints();
{chr(10).join(chunks)}
{user_alter}
{chr(10).join(fk_lines)}
        Schema::enableForeignKeyConstraints();
    }}

    public function down(): void
    {{
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {{
            $table->dropConstrainedForeignId('company_id');
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn(['phone', 'locale', 'user_type', 'is_active', 'two_factor_secret', 'two_factor_confirmed_at', 'last_login_at', 'last_login_ip', 'deleted_at']);
        }});
{down_tables}
        Schema::enableForeignKeyConstraints();
    }}
}};
"""


def cast_for(col: dict) -> str | None:
    t = col["type"]
    if t == "json":
        return "array"
    if t == "bool":
        return "boolean"
    if t == "date":
        return "date"
    if t == "datetime":
        return "datetime"
    if t == "qty":
        return "decimal:4"
    if t == "rate":
        return "decimal:6"
    return None


def model_php(spec: dict, by_table: dict) -> str:
    module = spec["module"]
    model = spec["model"]
    cols = full_columns(spec)
    fillable = ["public_id", "lock_version"] + [c["name"] for c in cols] + ["created_by", "updated_by"]
    fill = ",\n        ".join(php_string(f) for f in fillable)
    casts = []
    for c in cols:
        cast = cast_for(c)
        if cast:
            casts.append(f"            '{c['name']}' => '{cast}',")
    cast_body = "\n".join(casts)
    relations = []
    uses = set()
    for c in cols:
        if c["type"] != "fk":
            continue
        ref = by_table.get(c["ref"])
        if c["name"] == "parent_id":
            relations.append(
                "    public function parent(): BelongsTo\n    {\n        return $this->belongsTo(self::class, 'parent_id');\n    }\n"
            )
            uses.add("BelongsTo")
            continue
        if not ref:
            continue
        method = camel(c["name"][:-3]) if c["name"].endswith("_id") else camel(c["name"])
        if c["ref"] == "users":
            target = "\\App\\Models\\User"
        else:
            target = f"\\{ns_for(ref)}\\{ref['model']}"
        relations.append(
            f"    public function {method}(): BelongsTo\n    {{\n        return $this->belongsTo({target}::class, '{c['name']}');\n    }}\n"
        )
        uses.add("BelongsTo")
    seen = set()
    for other in by_table.values():
        if "model" not in other or other["model"] == model or other["table"] == "users":
            continue
        for c in full_columns(other):
            if c["type"] == "fk" and c["ref"] == spec["table"] and c["name"] != "parent_id":
                method = camel(other["model"])
                if not method.endswith("s"):
                    method += "s"
                if c["name"] != singular_fk(model):
                    method = camel(c["name"][:-3]) + studly(other["model"]) + "s"
                method = method[0].lower() + method[1:]
                if method in seen:
                    method = method + "Via" + studly(c["name"])
                seen.add(method)
                relations.append(
                    f"    public function {method}(): HasMany\n    {{\n        return $this->hasMany(\\{ns_for(other)}\\{other['model']}::class, '{c['name']}');\n    }}\n"
                )
                uses.add("HasMany")
    use_lines = ""
    if "BelongsTo" in uses:
        use_lines += "use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\n"
    if "HasMany" in uses:
        use_lines += "use Illuminate\\Database\\Eloquent\\Relations\\HasMany;\n"
    scoped = "false" if "plain" in spec["flags"] else "true"
    return f"""<?php

declare(strict_types=1);

namespace {ns_for(spec)};

use App\\Models\\MillModel;
{use_lines}
class {model} extends MillModel
{{
    protected $table = '{spec['table']}';

    protected bool $companyScoped = {scoped};

    /** @var list<string> */
    protected $fillable = [
        {fill}
    ];

    protected function casts(): array
    {{
        return [
{cast_body}
        ];
    }}

{chr(10).join(relations)}
}}
"""


def singular_fk(model: str) -> str:
    # Item -> item_id, SalesOrder -> sales_order_id
    s = re.sub(r"(?<!^)(?=[A-Z])", "_", model).lower()
    return s + "_id"


def ns_for(spec: dict) -> str:
    return f"App\\Modules\\{spec['module']}\\Models"


def policy_php(spec: dict) -> str:
    key = f"{MODULE_KEYS[spec['module']]}.{camel(spec['model'])}"
    return f"""<?php

declare(strict_types=1);

namespace App\\Policies;

use App\\Policies\\MillPolicy;

class {spec['model']}Policy extends MillPolicy
{{
    protected string $permission = '{key}';
}}
"""


def resource_php(spec: dict, by_table: dict) -> str:
    model = spec["model"]
    ns = ns_for(spec)
    cols = full_columns(spec)
    hidden = {
        "public_id", "company_id", "created_by", "updated_by", "lock_version",
        "qr_token",
    }
    fields = []
    columns = []
    for c in cols:
        if c["name"] in hidden:
            continue
        label = studly(c["name"]).replace("_", " ") if False else c["name"].replace("_", " ").title()
        fname = c["name"]
        if c["type"] == "fk":
            ref = by_table.get(c["ref"])
            if not ref:
                fields.append(f"            TextInput::make('{fname}')->numeric(),")
            else:
                rel = camel(fname[:-3]) if fname.endswith("_id") else camel(fname)
                title = title_column(ref)
                fields.append(
                    f"            Select::make('{fname}')->label('{label}')->relationship('{rel}', '{title}')->searchable()->preload(),"
                )
            columns.append(f"            TextColumn::make('{fname}')->label('{label}')->sortable()->toggleable(),")
            continue
        if c["type"] == "json":
            continue
        if c["name"] in OPTIONS:
            opts = OPTIONS.get(c["name"], "")
            pairs = ", ".join(f"'{o}' => '{o.replace('_', ' ')}'" for o in opts.split(",") if o)
            fields.append(f"            Select::make('{fname}')->label('{label}')->options([{pairs}])->native(false),")
        elif c["type"] == "text":
            fields.append(f"            Textarea::make('{fname}')->label('{label}')->columnSpanFull(),")
        elif c["type"] == "bool":
            fields.append(f"            Toggle::make('{fname}')->label('{label}'),")
        elif c["type"] == "date":
            fields.append(f"            DatePicker::make('{fname}')->label('{label}'),")
        elif c["type"] == "datetime":
            fields.append(f"            DateTimePicker::make('{fname}')->label('{label}'),")
        elif c["type"] == "json":
            fields.append(f"            Textarea::make('{fname}')->label('{label}')->columnSpanFull(),")
        elif c["type"] in ("qty", "rate", "int"):
            fields.append(f"            TextInput::make('{fname}')->label('{label}')->numeric(),")
        else:
            req = "" if c["null"] else "->required()"
            fields.append(f"            TextInput::make('{fname}')->label('{label}'){req}->maxLength(255),")
        if c["type"] != "text":
            columns.append(
                f"            TextColumn::make('{fname}')->label('{label}')->searchable()->sortable()->toggleable(),"
            )
    icon = ICONS.get(spec["module"], "OutlinedRectangleStack")
    group = GROUPS[spec["module"]]
    perm = f"{MODULE_KEYS[spec['module']]}.{camel(spec['model'])}"
    module_key = MODULE_KEYS[spec["module"]]
    return f"""<?php

declare(strict_types=1);

namespace App\\Filament\\Resources;

use App\\Filament\\Resources\\{model}Resource\\Pages\\Create{model};
use App\\Filament\\Resources\\{model}Resource\\Pages\\Edit{model};
use App\\Filament\\Resources\\{model}Resource\\Pages\\List{model}s;
use {ns}\\{model};
use App\\Support\\Modules\\ModuleRegistry;
use Filament\\Actions\\BulkActionGroup;
use Filament\\Actions\\DeleteBulkAction;
use Filament\\Actions\\EditAction;
use Filament\\Forms\\Components\\DatePicker;
use Filament\\Forms\\Components\\DateTimePicker;
use Filament\\Forms\\Components\\Select;
use Filament\\Forms\\Components\\Textarea;
use Filament\\Forms\\Components\\TextInput;
use Filament\\Forms\\Components\\Toggle;
use Filament\\Resources\\Resource;
use Filament\\Schemas\\Schema;
use Filament\\Support\\Icons\\Heroicon;
use Filament\\Tables\\Columns\\TextColumn;
use Filament\\Tables\\Filters\\SelectFilter;
use Filament\\Tables\\Table;

class {model}Resource extends Resource
{{
    protected static ?string $model = {model}::class;

    protected static string|\\BackedEnum|null $navigationIcon = Heroicon::{icon};

    protected static string|\\UnitEnum|null $navigationGroup = '{group}';

    protected static ?string $moduleKey = '{module_key}';

    protected static ?string $permission = '{perm}';

    public static function canAccess(): bool
    {{
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? '{module_key}')
            && $user->permits((static::$permission ?? '{perm}').'.view');
    }}

    public static function shouldRegisterNavigation(): bool
    {{
        return static::canAccess();
    }}

    public static function form(Schema $schema): Schema
    {{
        return $schema->components([
{chr(10).join(fields)}
        ]);
    }}

    public static function table(Table $table): Table
    {{
        return $table
            ->columns([
{chr(10).join(columns[:8])}
            ])
            ->filters([
                {("SelectFilter::make('status')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'posted' => 'Posted', 'closed' => 'Closed'])," if any(c["name"] == "status" for c in cols) else "")}
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }}

    public static function getPages(): array
    {{
        return [
            'index' => List{model}s::route('/'),
            'create' => Create{model}::route('/create'),
            'edit' => Edit{model}::route('/{{record}}/edit'),
        ];
    }}
}}
"""


def title_column(spec: dict) -> str:
    names = {c["name"] for c in full_columns(spec)}
    for candidate in ("name", "code", "number", "title", "lot_no", "roll_no", "beam_no", "employee_no"):
        if candidate in names:
            return candidate
    return "id"


def page_php(model: str, kind: str) -> str:
    base = {"List": "ListRecords", "Create": "CreateRecord", "Edit": "EditRecord"}[kind]
    class_name = f"{kind}{model}s" if kind == "List" else f"{kind}{model}"
    return f"""<?php

declare(strict_types=1);

namespace App\\Filament\\Resources\\{model}Resource\\Pages;

use App\\Filament\\Resources\\{model}Resource;
use Filament\\Resources\\Pages\\{base};

class {class_name} extends {base}
{{
    protected static string $resource = {model}Resource::class;
}}
"""


def module_json(module: str, tables: list) -> str:
    import json
    perms = []
    for spec in tables:
        if spec["module"] != module or "nomodel" in spec["flags"]:
            continue
        base = f"{MODULE_KEYS[module]}.{camel(spec['model'])}"
        for action in ("view", "create", "update", "delete", "approve"):
            perms.append(f"{base}.{action}")
    payload = {
        "key": MODULE_KEYS[module],
        "name": module,
        "dependencies": MODULE_DEPS.get(MODULE_KEYS[module], []),
        "permissions": perms,
        "menu": GROUPS[module],
    }
    return json.dumps(payload, indent=2) + "\n"


def provider_php(module: str) -> str:
    return f"""<?php

declare(strict_types=1);

namespace App\\Modules\\{module};

use App\\Support\\Modules\\ModuleRegistry;
use Illuminate\\Support\\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{{
    public function register(): void
    {{
        ModuleRegistry::register(__DIR__.'/module.json');
    }}
}}
"""


def main() -> None:
    tables = parse_schema()
    by_table = {t["table"]: t for t in tables}
    # users table referenced
    by_table["users"] = {"table": "users", "model": "User", "module": "Foundation", "flags": {"plain"}, "columns": []}
    write(
        ROOT / "database/migrations/2026_04_01_100000_create_mill_schema.php",
        migration(tables),
    )
    modules = sorted({t["module"] for t in tables})
    for module in modules:
        mods = [t for t in tables if t["module"] == module and "nomodel" not in t["flags"]]
        write(ROOT / f"app/Modules/{module}/module.json", module_json(module, tables))
        write(ROOT / f"app/Modules/{module}/ModuleServiceProvider.php", provider_php(module))
    for spec in tables:
        if "nomodel" in spec["flags"]:
            continue
        write(
            ROOT / f"app/Modules/{spec['module']}/Models/{spec['model']}.php",
            model_php(spec, by_table),
        )
        write(ROOT / f"app/Policies/{spec['model']}Policy.php", policy_php(spec))
        if "noui" in spec["flags"]:
            continue
        write(ROOT / f"app/Filament/Resources/{spec['model']}Resource.php", resource_php(spec, by_table))
        write(ROOT / f"app/Filament/Resources/{spec['model']}Resource/Pages/List{spec['model']}s.php", page_php(spec["model"], "List"))
        write(ROOT / f"app/Filament/Resources/{spec['model']}Resource/Pages/Create{spec['model']}.php", page_php(spec["model"], "Create"))
        write(ROOT / f"app/Filament/Resources/{spec['model']}Resource/Pages/Edit{spec['model']}.php", page_php(spec["model"], "Edit"))
    print(f"emitted {len(tables)} tables")


if __name__ == "__main__":
    main()

<?php

declare(strict_types=1);

namespace App\Modules\Issues;

/** Configurable mill rules from the issue-to-automation catalog. Thresholds are edited in the UI. */
final class RuleCatalog
{
    /** @return list<array<string, mixed>> */
    public static function definitions(): array
    {
        return [
            self::rule('purchase', 'Stale purchase requisition', 'pr.stale', '8', 'Escalate a requisition that has sat without a PO.'),
            self::rule('purchase', 'Auto comparative statement', 'quote.received', '0', 'Rank vendor quotes by price, lead time and score.'),
            self::rule('purchase', 'Cotton received without lab', 'cotton.received', '0', 'Hold the bale lot until micronaire, staple, strength, trash and moisture are posted.'),
            self::rule('inventory', 'Below reorder level', 'stock.level', '0', 'Raise a purchase requisition when available qty falls under the minimum.'),
            self::rule('inventory', 'Dead stock', 'stock.age', '180', 'Flag lots with no movement for the threshold days.'),
            self::rule('inventory', 'Cycle count variance', 'stock.variance', '1', 'Open an issue when the scan count differs from the book.'),
            self::rule('gate', 'Returnable gate pass overdue', 'gate.return', '0', 'Alert security when a returnable pass is past its due date.'),
            self::rule('gate', 'Weighbridge mismatch', 'gate.weight', '2', 'Open an issue when bridge weight and document weight differ by more than the percent.'),
            self::rule('spinning', 'Stage waste above standard', 'spinning.waste', '2', 'Compare stage waste with the standard and hold the lot when it drifts.'),
            self::rule('spinning', 'Count CV out of limit', 'yarn.cv', '2.5', 'Hold the yarn lot when CV% exceeds the limit.'),
            self::rule('spinning', 'Mixing deviation', 'mixing.deviation', '0.15', 'Alert when laydown micronaire drifts from the plan.'),
            self::rule('weaving', 'Beam not ready', 'beam.readiness', '0', 'Warn the shed before a loom runs out of beam.'),
            self::rule('weaving', 'Repeated loom stops', 'loom.stops', '8', 'Raise a maintenance issue when stop counts repeat.'),
            self::rule('dyeing', 'Redye above threshold', 'dye.redye', '5', 'Open an issue when redye percent for a recipe exceeds the limit.'),
            self::rule('dyeing', 'Chemical variance', 'dye.chemical', '3', 'Compare actual chemical kilograms with the recipe.'),
            self::rule('dyeing', 'Shade rejection', 'labdip.rejected', '2', 'Suggest a historical recipe after repeated buyer rejections.'),
            self::rule('finishing', 'Shrinkage out of spec', 'finish.shrinkage', '3', 'Block release when warp or weft shrinkage exceeds the spec.'),
            self::rule('quality', 'Repeat defect', 'quality.repeat', '3', 'Escalate a defect code that repeats inside the window.'),
            self::rule('planning', 'OTIF risk', 'plan.late', '2', 'Flag orders whose promise date is inside the risk window and still short.'),
            self::rule('maintenance', 'PM due by running hours', 'pm.due', '0', 'Create a preventive work order when running hours are due.'),
            self::rule('maintenance', 'Spare below minimum', 'spare.min', '0', 'Raise a requisition for a spare at minimum.'),
            self::rule('utilities', 'Consumption spike', 'utility.spike', '15', 'Compare kWh or steam per kilogram with the baseline.'),
            self::rule('utilities', 'Missing meter reading', 'utility.missing', '1', 'Remind the engineer when a meter has no reading.'),
            self::rule('hr', 'Missing punch', 'attendance.missing', '0', 'Open an issue for a shift with only one punch.'),
            self::rule('hr', 'OT over legal limit', 'attendance.ot', '60', 'Block payroll overtime above the weekly hour limit.'),
            self::rule('hr', 'Document expiry', 'document.expiry', '30', 'Remind at 90, 60, 30 and 7 days before a licence or training expires.'),
            self::rule('finance', 'LC maturity', 'lc.maturity', '7', 'Alert the bank desk before an LC or document deadline.'),
            self::rule('finance', 'Receivable overdue', 'ar.overdue', '0', 'Send a collection reminder when an invoice passes its due date.'),
            self::rule('sales', 'Credit limit breach', 'credit.limit', '0', 'Block the sales order or delivery when exposure exceeds the limit.'),
            self::rule('compliance', 'Certificate expiry', 'certificate.expiry', '30', 'Put the certificate on the compliance calendar.'),
            self::rule('system', 'Duplicate document', 'document.duplicate', '0', 'Stop a second document with the same party, date and total.'),
        ];
    }

    /** @return array<string, mixed> */
    private static function rule(string $module, string $name, string $trigger, string $threshold, string $recommendation): array
    {
        return [
            'module_key' => $module,
            'name' => $name,
            'trigger' => $trigger,
            'threshold' => $threshold,
            'conditions' => ['threshold' => $threshold],
            'actions' => ['create_issue' => true, 'notify' => ['owner', 'supervisor']],
            'recommendation' => $recommendation,
            'is_active' => true,
            'auto_execute' => in_array($trigger, ['stock.level', 'cotton.received', 'finish.shrinkage', 'attendance.ot', 'credit.limit'], true),
        ];
    }
}

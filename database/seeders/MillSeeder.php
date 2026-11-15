<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MillModel;
use App\Models\User;
use App\Modules\Commercial\Models\Party;
use App\Modules\Finance\Models\Account;
use App\Modules\Finance\Models\AccountMapping;
use App\Modules\Finance\Models\FiscalYear;
use App\Modules\Foundation\Models\Branch;
use App\Modules\Foundation\Models\Company;
use App\Modules\Foundation\Models\CompanyModule;
use App\Modules\Foundation\Models\Department;
use App\Modules\Foundation\Models\Permission;
use App\Modules\Foundation\Models\Role;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Issues\Models\AutomationRule;
use App\Modules\Issues\RuleCatalog;
use App\Modules\Master\Models\Currency;
use App\Modules\Master\Models\Designation;
use App\Modules\Master\Models\Incoterm;
use App\Modules\Master\Models\Item;
use App\Modules\Master\Models\Machine;
use App\Modules\Master\Models\PaymentTerm;
use App\Modules\Master\Models\Shift;
use App\Modules\Master\Models\Tax;
use App\Modules\Master\Models\UnitOfMeasure;
use App\Modules\People\Models\Employee;
use App\Modules\Website\Models\CmsPage;
use App\Services\OrderFlowService;
use App\Support\Context\TenantContext;
use App\Support\Modules\ModuleRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MillSeeder extends Seeder
{
    public function run(): void
    {
        MillModel::$auditing = false;
        DB::transaction(function (): void {
            $this->seed();
        });
        MillModel::$auditing = true;
    }

    private function seed(): void
    {
        $company = Company::query()->create([
            'code' => 'PADMA',
            'name' => 'Padma Textile Mills Ltd',
            'legal_name' => 'Padma Textile Mills Limited',
            'brand_name' => 'Padma Textile',
            'country' => 'BD',
            'currency_code' => 'BDT',
            'timezone' => 'Asia/Dhaka',
            'fiscal_start_month' => 7,
            'vat_rate' => '15',
            'valuation_method' => 'weighted_average',
            'address' => 'Beximco Industrial Park, Gazipur, Bangladesh',
            'phone' => '+8801711000000',
            'email' => 'hello@padma-textile.test',
        ]);
        TenantContext::set($company->id);

        $spinning = $this->branch($company, 'SP1', 'Spinning Unit 1', 'spinning');
        $weaving = $this->branch($company, 'WV1', 'Weaving Unit 2', 'weaving');
        $this->branch($company, 'DY1', 'Dyeing Unit', 'dyeing');

        foreach (ModuleRegistry::all() as $key => $module) {
            CompanyModule::query()->create([
                'company_id' => $company->id,
                'module_key' => $key,
                'enabled' => true,
                'settings' => ['dependencies' => $module['dependencies'] ?? []],
            ]);
        }

        $role = Role::query()->create([
            'company_id' => $company->id,
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'is_system' => true,
        ]);
        foreach (ModuleRegistry::permissions() as $name) {
            [$module, $resource, $action] = array_pad(explode('.', $name), 3, 'view');
            $permission = Permission::query()->firstOrCreate(
                ['name' => $name],
                ['module' => $module, 'resource' => $resource, 'action' => $action],
            );
            DB::table('role_permission')->insert([
                'public_id' => (string) Str::ulid(),
                'role_id' => $role->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $admin = User::query()->create([
            'name' => 'Mill Admin',
            'email' => 'admin@padma-textile.test',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'branch_id' => $spinning->id,
            'locale' => 'en',
            'user_type' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        DB::table('user_role')->insert([
            'public_id' => (string) Str::ulid(),
            'user_id' => $admin->id,
            'role_id' => $role->id,
            'branch_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::query()->create([
            'name' => 'Buyer Desk',
            'email' => 'buyer@padma-textile.test',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'locale' => 'en',
            'user_type' => 'buyer',
            'is_active' => true,
        ]);

        $kg = UnitOfMeasure::query()->create(['company_id' => $company->id, 'code' => 'KG', 'name' => 'Kilogram', 'dimension' => 'mass']);
        UnitOfMeasure::query()->create(['company_id' => $company->id, 'code' => 'MTR', 'name' => 'Meter', 'dimension' => 'length']);
        UnitOfMeasure::query()->create(['company_id' => $company->id, 'code' => 'BALE', 'name' => 'Bale', 'dimension' => 'mass']);
        Currency::query()->create(['company_id' => $company->id, 'code' => 'BDT', 'name' => 'Taka', 'symbol' => '৳', 'is_base' => true]);
        Currency::query()->create(['company_id' => $company->id, 'code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        Tax::query()->create(['company_id' => $company->id, 'code' => 'VAT15', 'name' => 'VAT', 'rate' => '15', 'kind' => 'vat']);
        $term = PaymentTerm::query()->create(['company_id' => $company->id, 'code' => 'LC30', 'name' => 'Sight LC', 'days' => 0]);
        Incoterm::query()->create(['company_id' => $company->id, 'code' => 'FOB', 'name' => 'Free On Board']);
        $department = Department::query()->create(['company_id' => $company->id, 'branch_id' => $spinning->id, 'code' => 'PRD', 'name' => 'Production']);
        $designation = Designation::query()->create(['company_id' => $company->id, 'name' => 'Ring Spinner', 'grade' => 'W-3']);
        $shift = Shift::query()->create([
            'company_id' => $company->id,
            'branch_id' => $spinning->id,
            'code' => 'A',
            'name' => 'Shift A',
            'starts_at' => '06:00',
            'ends_at' => '14:00',
        ]);

        $accounts = [
            '1100' => ['Bank', 'asset'],
            '1200' => ['Accounts receivable', 'asset'],
            '1300' => ['Inventory', 'asset'],
            '2000' => ['Accounts payable', 'liability'],
            '2100' => ['VAT payable', 'liability'],
            '4000' => ['Sales', 'income'],
            '5000' => ['Cost of goods', 'expense'],
            '5200' => ['Payroll', 'expense'],
        ];
        $ids = [];
        foreach ($accounts as $code => [$name, $type]) {
            $ids[$code] = Account::query()->create([
                'company_id' => $company->id,
                'code' => $code,
                'name' => $name,
                'account_type' => $type,
                'is_postable' => true,
            ])->id;
        }
        AccountMapping::query()->create(['company_id' => $company->id, 'event_key' => 'sales.invoice', 'debit_account_id' => $ids['1200'], 'credit_account_id' => $ids['4000']]);
        AccountMapping::query()->create(['company_id' => $company->id, 'event_key' => 'sales.receipt', 'debit_account_id' => $ids['1100'], 'credit_account_id' => $ids['1200']]);
        AccountMapping::query()->create(['company_id' => $company->id, 'event_key' => 'inventory.receipt', 'debit_account_id' => $ids['1300'], 'credit_account_id' => $ids['2000']]);
        FiscalYear::query()->create([
            'company_id' => $company->id,
            'name' => '2026-2027',
            'starts_on' => '2026-07-01',
            'ends_on' => '2027-06-30',
        ]);

        $warehouse = Warehouse::query()->create([
            'company_id' => $company->id,
            'branch_id' => $spinning->id,
            'code' => 'RAW',
            'name' => 'Raw cotton godown',
            'kind' => 'raw_cotton',
        ]);
        Warehouse::query()->create(['company_id' => $company->id, 'branch_id' => $weaving->id, 'code' => 'YARN', 'name' => 'Yarn godown', 'kind' => 'yarn']);
        Warehouse::query()->create(['company_id' => $company->id, 'branch_id' => $weaving->id, 'code' => 'FIN', 'name' => 'Finished fabric store', 'kind' => 'finished']);

        $cotton = $this->item($company->id, $kg->id, 'COT-SHANKAR-6', 'Shankar-6 cotton', 'raw_material');
        $yarn = $this->item($company->id, $kg->id, 'YRN-30-CW', '30s carded weaving', 'yarn', ['count_ne' => '30', 'tpi' => '22.5', 'ply' => 1]);
        $fabric = $this->item($company->id, $kg->id, 'FB-20X16', '20x16 / 128x60 poplin', 'greige', ['construction' => '20x16 / 128x60', 'weave' => 'plain', 'gsm' => '120', 'epi' => '128', 'ppi' => '60', 'width_inch' => '63']);
        $dye = $this->item($company->id, $kg->id, 'DYE-NAVY', 'Reactive navy', 'dye', ['shade_code' => '19-3920']);
        $this->catalog($company->id, $kg->id);

        $buyer = Party::query()->create([
            'company_id' => $company->id,
            'code' => 'BUY-HNM',
            'name' => 'Northwind Apparel',
            'party_type' => 'buyer',
            'country' => 'DE',
            'currency_code' => 'USD',
            'credit_limit' => '250000',
            'payment_term_id' => $term->id,
            'email' => 'buyer@padma-textile.test',
        ]);
        $supplier = Party::query()->create([
            'company_id' => $company->id,
            'code' => 'SUP-COTTON',
            'name' => 'Dhaka Cotton Traders',
            'party_type' => 'supplier',
            'currency_code' => 'USD',
            'email' => 'cotton@example.test',
        ]);

        $ring = Machine::query()->create([
            'company_id' => $company->id, 'branch_id' => $spinning->id, 'code' => 'RF-12', 'name' => 'Ring frame 12',
            'section' => 'ring', 'make' => 'LR', 'model' => 'LRJ9', 'spindle_count' => 1008, 'status' => 'running',
        ]);
        $loom = Machine::query()->create([
            'company_id' => $company->id, 'branch_id' => $weaving->id, 'code' => 'LM-04', 'name' => 'Rapier 04',
            'section' => 'loom', 'make' => 'Picanol', 'status' => 'running',
        ]);
        $jet = Machine::query()->create([
            'company_id' => $company->id, 'branch_id' => $weaving->id, 'code' => 'JET-02', 'name' => 'Jet 02',
            'section' => 'dyeing', 'make' => 'Thies', 'capacity' => '400', 'status' => 'running',
        ]);
        $employee = Employee::query()->create([
            'company_id' => $company->id, 'branch_id' => $spinning->id, 'employee_no' => 'E-1001',
            'name' => 'Karim Mia', 'designation_id' => $designation->id, 'department_id' => $department->id,
            'category' => 'permanent', 'join_date' => '2022-03-01', 'basic_salary' => '18000', 'status' => 'active',
        ]);
        foreach (range(2, 50) as $n) {
            Employee::query()->create([
                'company_id' => $company->id,
                'branch_id' => $spinning->id,
                'employee_no' => 'E-'.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                'name' => 'Worker '.$n,
                'category' => $n % 5 === 0 ? 'piece_rate' : 'permanent',
                'basic_salary' => (string) (15000 + ($n * 10)),
                'status' => 'active',
                'department_id' => $department->id,
            ]);
        }

        foreach (RuleCatalog::definitions() as $rule) {
            AutomationRule::query()->create([
                'company_id' => $company->id,
                'module_key' => $rule['module_key'],
                'name' => $rule['name'],
                'trigger' => $rule['trigger'],
                'conditions' => $rule['conditions'],
                'actions' => $rule['actions'],
                'threshold' => $rule['threshold'],
                'is_active' => true,
                'auto_execute' => $rule['auto_execute'],
            ]);
        }

        $this->pages($company->id);

        app(OrderFlowService::class)->run([
            'company' => $company,
            'branchId' => $spinning->id,
            'buyer' => $buyer,
            'supplier' => $supplier,
            'cotton' => $cotton,
            'yarn' => $yarn,
            'fabric' => $fabric,
            'dye' => $dye,
            'warehouse' => $warehouse,
            'ring' => $ring,
            'loom' => $loom,
            'jet' => $jet,
            'user' => $admin,
            'shift' => $shift,
            'employee' => $employee,
        ]);
    }

    private function branch(Company $company, string $code, string $name, string $type): Branch
    {
        return Branch::query()->create([
            'company_id' => $company->id,
            'code' => $code,
            'name' => $name,
            'unit_type' => $type,
            'is_active' => true,
        ]);
    }

    /** @param array<string, mixed> $extra */
    private function item(int $companyId, int $uomId, string $code, string $name, string $type, array $extra = []): Item
    {
        return Item::query()->create([
            'company_id' => $companyId,
            'code' => $code,
            'name' => $name,
            'slug' => universal_slug($name.' '.$code),
            'item_type' => $type,
            'uom_id' => $uomId,
            ...$extra,
        ]);
    }

    private function catalog(int $companyId, int $uomId): void
    {
        $counts = [16, 20, 24, 30, 32, 40];
        $n = 0;
        foreach ($counts as $count) {
            foreach (['carded', 'combed', 'compact'] as $process) {
                $n++;
                $this->item($companyId, $uomId, 'YRN-'.$count.'-'.strtoupper(substr($process, 0, 3)), $count.'s '.$process.' cotton', 'yarn', [
                    'count_ne' => (string) $count,
                    'tpi' => (string) (18 + ($count / 10)),
                ]);
            }
        }
        foreach (['plain', 'twill', 'satin', 'denim'] as $weave) {
            for ($gsm = 100; $gsm <= 280 && $n < 200; $gsm += 20) {
                $n++;
                $this->item($companyId, $uomId, 'FB-'.$weave.'-'.$gsm, ucfirst($weave).' '.$gsm.' GSM', $gsm > 160 ? 'finished_fabric' : 'greige', [
                    'weave' => $weave,
                    'gsm' => (string) $gsm,
                    'construction' => '40x40 / 133x72',
                ]);
            }
        }
        foreach (['salt', 'soda', 'caustic', 'peroxide', 'softener', 'enzyme'] as $chemical) {
            $this->item($companyId, $uomId, 'CH-'.strtoupper($chemical), ucfirst($chemical), 'chemical');
        }
        for ($i = 1; $i <= 160; $i++) {
            $type = match ($i % 4) {
                0 => 'spare',
                1 => 'dye',
                2 => 'packing',
                default => 'consumable',
            };
            $this->item($companyId, $uomId, 'CAT-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT), 'Catalog item '.$i, $type);
        }
    }

    private function pages(int $companyId): void
    {
        $pages = [
            ['home', 'Home', 'Integrated spinning, weaving and dyeing in Gazipur.'],
            ['about', 'About', 'Padma Textile runs cotton to dyed fabric under one roof.'],
            ['quality', 'Quality', 'Uster testing, 4-point inspection and buyer lab dips.'],
            ['sustainability', 'Sustainability', 'ETP, effluent logs and OEKO-TEX ready traceability.'],
        ];
        foreach ($pages as [$slug, $title, $body]) {
            CmsPage::query()->create([
                'company_id' => $companyId,
                'title' => $title,
                'slug' => $slug,
                'locale' => 'en',
                'excerpt' => $body,
                'body' => $body,
                'seo_title' => $title.' · Padma Textile',
                'seo_description' => $body,
                'published' => true,
            ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Commercial\Models\Inquiry;
use App\Modules\Commercial\Models\SalesOrder;
use App\Modules\Foundation\Models\Company;
use App\Modules\Master\Models\Item;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Website\Models\ContactMessage;
use App\Modules\Website\Models\JobPosting;
use App\Services\NumberSeriesService;
use App\Support\Context\TenantContext;
use App\Support\Integrations\Contracts\SmsGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function home(): View
    {
        $company = $this->company();
        if ($company) {
            TenantContext::set($company->id);
        }

        return view('website.home', [
            'company' => $company,
            'pages' => $company ? CmsPage::query()->where('published', true)->where('locale', app()->getLocale())->get() : collect(),
            'yarns' => $company ? Item::query()->where('item_type', 'yarn')->limit(6)->get() : collect(),
        ]);
    }

    public function page(string $slug): View
    {
        $company = $this->company();
        abort_unless($company, 404);
        TenantContext::set($company->id);
        $page = CmsPage::query()->where('slug', $slug)->where('published', true)->firstOrFail();

        return view('website.page', ['company' => $company, 'page' => $page]);
    }

    public function products(Request $request): View
    {
        $company = $this->company();
        abort_unless($company, 404);
        TenantContext::set($company->id);
        $type = $request->string('type')->toString() ?: 'yarn';
        $items = Item::query()->where('item_type', $type)->orderBy('name')->paginate(24);

        return view('website.products', ['company' => $company, 'items' => $items, 'type' => $type]);
    }

    public function contact(): View
    {
        return view('website.contact', ['company' => $this->company()]);
    }

    public function sendContact(Request $request, NumberSeriesService $numbers): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
            'company_website' => ['nullable', 'size:0'],
        ]);
        $company = $this->company();
        abort_unless($company, 404);
        TenantContext::set($company->id);

        ContactMessage::query()->create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
        ]);
        Inquiry::query()->create([
            'company_id' => $company->id,
            'number' => $numbers->next('inquiry', $company->id),
            'status' => 'open',
            'document_date' => now()->toDateString(),
            'subject' => 'Website enquiry from '.$data['name'],
            'source' => 'website',
            'contact_name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'notes' => $data['message'],
        ]);

        return back()->with('status', __('mill.contact_sent'));
    }

    public function quote(Request $request, NumberSeriesService $numbers): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'item' => ['required', 'string', 'max:180'],
            'qty' => ['required', 'numeric', 'min:1'],
            'company_website' => ['nullable', 'size:0'],
        ]);
        $company = $this->company();
        abort_unless($company, 404);
        TenantContext::set($company->id);
        Inquiry::query()->create([
            'company_id' => $company->id,
            'number' => $numbers->next('inquiry', $company->id),
            'status' => 'open',
            'document_date' => now()->toDateString(),
            'subject' => 'Quote: '.$data['item'].' × '.$data['qty'],
            'source' => 'website',
            'contact_name' => $data['name'],
            'email' => $data['email'],
            'notes' => $data['item'].' qty '.$data['qty'],
        ]);

        return back()->with('status', __('mill.quote_sent'));
    }

    public function trackForm(): View
    {
        return view('website.track', ['company' => $this->company(), 'order' => null]);
    }

    public function track(Request $request, SmsGateway $sms): View
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:40'],
            'otp' => ['nullable', 'string', 'max:8'],
        ]);
        $company = $this->company();
        abort_unless($company, 404);
        TenantContext::set($company->id);
        $order = SalesOrder::query()->where('number', $data['number'])->first();
        abort_unless($order, 404);

        $key = 'track:'.$order->number;
        if (empty($data['otp'])) {
            $otp = (string) random_int(100000, 999999);
            Cache::put($key, $otp, now()->addMinutes((int) config('mill.otp_ttl_minutes')));
            $sms->send((string) ($order->party?->phone ?: 'desk'), 'Padma Textile order '.$order->number.' OTP '.$otp);
            $request->session()->flash('otp_sent', true);
            if (app()->environment('local')) {
                $request->session()->flash('demo_otp', $otp);
            }

            return view('website.track', ['company' => $company, 'order' => null, 'number' => $order->number]);
        }

        abort_unless(hash_equals((string) Cache::get($key, ''), $data['otp']), 403);

        return view('website.track', ['company' => $company, 'order' => $order->load('party')]);
    }

    public function careers(): View
    {
        $company = $this->company();
        if ($company) {
            TenantContext::set($company->id);
        }

        return view('website.careers', [
            'company' => $company,
            'jobs' => $company ? JobPosting::query()->where('is_open', true)->get() : collect(),
        ]);
    }

    public function sitemap(): Response
    {
        $company = $this->company();
        $urls = [url('/')];
        if ($company) {
            TenantContext::set($company->id);
            foreach (CmsPage::query()->where('published', true)->pluck('slug') as $slug) {
                $urls[] = url('/'.$slug);
            }
        }
        $xml = view('website.sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']);
    }

    private function company(): ?Company
    {
        return Company::query()->first();
    }
}

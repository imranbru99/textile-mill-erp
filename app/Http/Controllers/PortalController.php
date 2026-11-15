<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Commercial\Models\Party;
use App\Modules\Commercial\Models\SalesOrder;
use App\Modules\Dyeing\Models\LabDip;
use App\Modules\Finance\Models\SalesInvoice;
use App\Modules\Supply\Models\PurchaseOrder;
use App\Support\Context\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function loginForm(): View
    {
        return view('portal.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        if (! Auth::attempt($data, true)) {
            return back()->withErrors(['email' => 'Those credentials do not match a mill user.']);
        }
        $request->session()->regenerate();
        $type = $request->user()->user_type;

        return redirect($type === 'supplier' ? '/desk/supplier' : ($type === 'buyer' ? '/desk/buyer' : '/admin'));
    }

    public function buyer(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->user_type === 'buyer', 403);
        TenantContext::set((int) $user->company_id);
        $party = Party::query()->where('email', $user->email)->first();
        $orders = $party ? SalesOrder::query()->where('party_id', $party->id)->latest('id')->get() : collect();
        $dips = $party ? LabDip::query()->where('party_id', $party->id)->latest('id')->get() : collect();
        $invoices = $party ? SalesInvoice::query()->where('party_id', $party->id)->latest('id')->get() : collect();

        return view('portal.buyer', compact('orders', 'dips', 'invoices', 'party'));
    }

    public function decideDip(Request $request, LabDip $labDip): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->user_type === 'buyer', 403);
        TenantContext::set((int) $user->company_id);
        $data = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);
        $labDip->buyer_decision = $data['decision'];
        $labDip->status = $data['decision'];
        $labDip->notes = $data['comment'] ?? $labDip->notes;
        $labDip->save();

        return back();
    }

    public function supplier(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->user_type === 'supplier', 403);
        TenantContext::set((int) $user->company_id);
        $party = Party::query()->where('email', $user->email)->first();
        $orders = $party ? PurchaseOrder::query()->where('party_id', $party->id)->latest('id')->get() : collect();

        return view('portal.supplier', compact('orders', 'party'));
    }
}

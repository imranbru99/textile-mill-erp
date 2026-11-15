@extends('admin-panel::layouts.app')
@section('content')
    <h1 class="text-2xl font-semibold">Buyer desk</h1>
    <p class="mt-1 text-sm">{{ $party->name ?? 'No party linked to this login.' }}</p>
    <h2 class="mt-6 font-medium">Orders</h2>
    <ul class="mt-2 space-y-2">
        @foreach ($orders as $order)
            <li class="rounded border p-3 text-sm">{{ $order->number }} · {{ $order->status }} · {{ $order->currency_code }} {{ $order->total_amount }} · promise {{ $order->delivery_date }}</li>
        @endforeach
    </ul>
    <h2 class="mt-6 font-medium">Lab dips</h2>
    @foreach ($dips as $dip)
        <form method="post" action="{{ url('/desk/buyer/lab-dips/'.$dip->id) }}" class="mt-2 flex flex-wrap items-center gap-2 rounded border p-3 text-sm">
            @csrf
            <span>{{ $dip->number }} · {{ $dip->shade_name }} · ΔE {{ $dip->delta_e }} · {{ $dip->buyer_decision }}</span>
            <select name="decision" class="rounded border px-2 py-1">
                <option value="approved">Approve</option>
                <option value="rejected">Reject</option>
            </select>
            <input name="comment" placeholder="Comment" class="rounded border px-2 py-1">
            <button class="rounded bg-stone-900 px-3 py-1 text-white">Send</button>
        </form>
    @endforeach
    <h2 class="mt-6 font-medium">Invoices</h2>
    <ul class="mt-2 space-y-2">
        @foreach ($invoices as $invoice)
            <li class="text-sm">{{ $invoice->number }} · {{ $invoice->grand_total }} · due {{ $invoice->due_date }}</li>
        @endforeach
    </ul>
@endsection

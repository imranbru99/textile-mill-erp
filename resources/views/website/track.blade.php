@extends('website.layout', ['title' => 'Track order'])
@section('content')
    <h1 class="text-3xl font-semibold">{{ __('mill.track') }}</h1>
    <form method="post" action="{{ url('/track') }}" class="mt-6 flex max-w-xl gap-2">
        @csrf
        <input name="number" value="{{ $number ?? '' }}" required placeholder="Sales order number" class="flex-1 rounded border px-3 py-2">
        <input name="otp" placeholder="OTP" class="w-28 rounded border px-3 py-2">
        <button class="rounded bg-stone-900 px-4 py-2 text-white">Open</button>
    </form>
    @if (session('otp_sent'))
        <p class="mt-4 text-sm">A one-time code was sent. @if (session('demo_otp')) Demo code: {{ session('demo_otp') }} @endif</p>
    @endif
    @if ($order)
        <dl class="mt-8 grid max-w-lg gap-2 rounded border bg-white p-4 text-sm">
            <div class="flex justify-between"><dt>Order</dt><dd>{{ $order->number }}</dd></div>
            <div class="flex justify-between"><dt>Status</dt><dd>{{ $order->status }}</dd></div>
            <div class="flex justify-between"><dt>Buyer</dt><dd>{{ $order->party?->name }}</dd></div>
            <div class="flex justify-between"><dt>Promise</dt><dd>{{ $order->delivery_date }}</dd></div>
            <div class="flex justify-between"><dt>Amount</dt><dd>{{ $order->currency_code }} {{ $order->total_amount }}</dd></div>
        </dl>
    @endif
@endsection

@extends('admin-panel::layouts.app')
@section('content')
    <h1 class="text-2xl font-semibold">Supplier desk</h1>
    <p class="mt-1 text-sm">{{ $party->name ?? 'No supplier linked to this login.' }}</p>
    <ul class="mt-4 space-y-2">
        @foreach ($orders as $order)
            <li class="rounded border p-3 text-sm">{{ $order->number }} · {{ $order->status }} · {{ $order->currency_code }} {{ $order->total_amount }}</li>
        @endforeach
    </ul>
@endsection

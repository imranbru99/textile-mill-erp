@extends('website.layout')
@section('content')
    <p class="text-sm uppercase tracking-widest text-amber-800">Gazipur · Cotton to dyed fabric</p>
    <h1 class="mt-2 max-w-3xl text-4xl font-semibold leading-tight">{{ $company->name ?? config('mill.brand') }}</h1>
    <p class="mt-4 max-w-2xl text-stone-600">Ring spinning, rapier weaving and jet dyeing with lot traceability from bale to invoice. Bangla and English documents, bonded warehouse and back-to-back L/C.</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        @foreach ([['1008', 'Spindles on RF-12'], ['550', 'Loom RPM'], ['400 kg', 'Jet capacity']] as [$stat, $label])
            <div class="rounded border border-stone-200 bg-white p-4">
                <div class="text-2xl font-semibold">{{ $stat }}</div>
                <div class="text-sm text-stone-500">{{ $label }}</div>
            </div>
        @endforeach
    </div>
    <h2 class="mt-12 text-xl font-semibold">Yarn counts</h2>
    <ul class="mt-4 grid gap-3 sm:grid-cols-3">
        @foreach ($yarns as $yarn)
            <li class="rounded border border-stone-200 bg-white p-4">
                <div class="font-medium">{{ $yarn->name }}</div>
                <div class="text-sm text-stone-500">Ne {{ $yarn->count_ne }} · {{ $yarn->code }}</div>
            </li>
        @endforeach
    </ul>
@endsection

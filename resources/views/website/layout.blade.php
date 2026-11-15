<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($company->brand_name ?? config('mill.brand')) }}</title>
    <meta name="description" content="{{ $description ?? 'Spinning, weaving and dyeing under one roof in Gazipur.' }}">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="bn" href="{{ url()->current() }}?lang=bn">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-50 text-stone-900">
    <header class="border-b border-stone-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight">{{ $company->brand_name ?? config('mill.brand') }}</a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ url('/about') }}">{{ __('mill.about') }}</a>
                <a href="{{ url('/products') }}">{{ __('mill.products') }}</a>
                <a href="{{ url('/careers') }}">{{ __('mill.careers') }}</a>
                <a href="{{ url('/contact') }}">{{ __('mill.contact') }}</a>
                <a href="{{ url('/track') }}">{{ __('mill.track') }}</a>
                <a href="{{ url('/desk') }}">{{ __('mill.portal') }}</a>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-4 py-10">
        @if (session('status'))
            <p class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>
    <footer class="border-t border-stone-200 py-6 text-center text-xs text-stone-500">
        {{ $company->address ?? 'Gazipur, Bangladesh' }} · VAT {{ $company->vat_rate ?? '15' }}%
    </footer>
</body>
</html>

@extends('website.layout', ['title' => 'Contact'])
@section('content')
    <h1 class="text-3xl font-semibold">{{ __('mill.contact') }}</h1>
    <form method="post" action="{{ url('/contact') }}" class="mt-6 max-w-lg space-y-3">
        @csrf
        <input type="text" name="company_website" class="hidden" tabindex="-1" autocomplete="off">
        <input name="name" required placeholder="Name" class="w-full rounded border px-3 py-2">
        <input name="email" type="email" required placeholder="Email" class="w-full rounded border px-3 py-2">
        <input name="phone" placeholder="Phone" class="w-full rounded border px-3 py-2">
        <textarea name="message" required rows="5" class="w-full rounded border px-3 py-2" placeholder="Message"></textarea>
        <button class="rounded bg-stone-900 px-4 py-2 text-white">Send</button>
    </form>
    @if ($errors->any())
        <ul class="mt-4 text-sm text-red-700">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
@endsection

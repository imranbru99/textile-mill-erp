@extends('website.layout', ['title' => 'Products'])
@section('content')
    <h1 class="text-3xl font-semibold">{{ __('mill.products') }}</h1>
    <div class="mt-4 flex gap-3 text-sm">
        @foreach (['yarn' => 'Yarn', 'greige' => 'Greige', 'finished_fabric' => 'Finished', 'dye' => 'Dyes'] as $key => $label)
            <a class="{{ $type === $key ? 'font-semibold' : '' }}" href="{{ url('/products?type='.$key) }}">{{ $label }}</a>
        @endforeach
    </div>
    <ul class="mt-6 divide-y divide-stone-200 rounded border border-stone-200 bg-white">
        @foreach ($items as $item)
            <li class="flex items-center justify-between px-4 py-3">
                <div>
                    <div class="font-medium">{{ $item->name }}</div>
                    <div class="text-xs text-stone-500">{{ $item->code }} {{ $item->construction }}</div>
                </div>
                <form method="post" action="{{ url('/quote') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="company_website" class="hidden" tabindex="-1" autocomplete="off">
                    <input type="hidden" name="item" value="{{ $item->code }}">
                    <input name="name" placeholder="Name" required class="w-28 rounded border px-2 py-1 text-sm">
                    <input name="email" type="email" placeholder="Email" required class="w-36 rounded border px-2 py-1 text-sm">
                    <input name="qty" type="number" min="1" value="1000" class="w-24 rounded border px-2 py-1 text-sm">
                    <button class="rounded bg-stone-900 px-3 py-1 text-sm text-white">Quote</button>
                </form>
            </li>
        @endforeach
    </ul>
    <div class="mt-4">{{ $items->links() }}</div>
@endsection

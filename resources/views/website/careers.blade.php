@extends('website.layout', ['title' => 'Careers'])
@section('content')
    <h1 class="text-3xl font-semibold">{{ __('mill.careers') }}</h1>
    <ul class="mt-6 space-y-3">
        @forelse ($jobs as $job)
            <li class="rounded border bg-white p-4">
                <div class="font-medium">{{ $job->title }}</div>
                <div class="text-sm text-stone-500">{{ $job->department }} · {{ $job->location }}</div>
                <p class="mt-2 text-sm">{{ $job->body }}</p>
            </li>
        @empty
            <li class="text-sm text-stone-500">No open posts. Write to the mill office.</li>
        @endforelse
    </ul>
@endsection

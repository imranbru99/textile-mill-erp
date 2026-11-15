@extends('website.layout', ['title' => $page->seo_title ?: $page->title, 'description' => $page->seo_description])
@section('content')
    <h1 class="text-3xl font-semibold">{{ $page->title }}</h1>
    <p class="mt-4 max-w-3xl leading-7 text-stone-700">{{ $page->body }}</p>
@endsection

@extends('layouts.app')

@section('content')
@foreach(get_category_items('blog') as $item)
    <h2>{{ $item->title }}</h2>
    <h2>{!! $item->body !!}</h2>
    {{ $item->keywords }}
        <p>{{ $item->description }}</p>



        <img src="{{ $item->image }}" alt="{{ $item->title }}">

    <a href="{{ url($item->slug) }}">Devamını oku</a>
@endforeach
{{ site_setting('title') }}



@php
$currentLocale = App::getLocale();
$services = get_homepage('rakamlar');
@endphp

<section id="services">
    <h2>{{ $services->title ?? 'Default Title' }}</h2>
    <p>{{ $services->excerpt ?? 'Default Description' }}</p>
    {!! $services->content ?? 'Default Content' !!}
</section>

@endsection

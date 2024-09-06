@extends('layouts.app')

@section('content')
@foreach(get_category_items('blog') as $item)
    <h2>{{ $item->title }}</h2>
    <h2>{{ $item->body }}</h2>
    {{ $item->keywords }}
        <p>{{ $item->description }}</p>



        <img src="{{ $item->image }}" alt="{{ $item->title }}">

    <a href="{{ url($item->slug) }}">Devamını oku</a>
@endforeach
{{ site_setting('title') }}


@php
    dd(get_homepage('rakamlar')->en->num1)
@endphp


<section id="services">
    <h2></h2>
</section>

@endsection

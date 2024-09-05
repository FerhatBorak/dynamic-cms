@extends('layouts.app')

@section('content')
@foreach(get_category_items('egitim-gruplari') as $item)
    <h2>{{ $item->title }}</h2>
    @if(isset($item->keywords))
        <p>{{ $item->description }}</p>
    @endif


        <img src="{{ $item->image }}" alt="{{ $item->title }}">

    <a href="{{ url($item->slug) }}">Devamını oku</a>
@endforeach
{{ site_setting('title') }}
@endsection

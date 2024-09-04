@extends('layouts.app')

@section('content')
@foreach(get_category_items('blog') as $item)
    <h2>{{ $item->title }}</h2>
    @if(isset($item->body))
        <p>{{ $item->body }}</p>
    @endif
                @php
                    dd(site_setting);
                @endphp

        <img src="{{ $item->image }}" alt="{{ $item->title }}">

    <a href="{{ url($item->slug) }}">Devamını oku</a>
@endforeach
{{ site_setting('title') }}
@endsection

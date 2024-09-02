@extends('layouts.app')

@section('content')
@foreach(get_category_items('blog') as $item)
    <h2>{{ $item->title }}</h2>

   <a href="{{ url($item->slug) }}">Devamını oku</a></
@endforeach
@endsection

@extends('layouts.app')
@section('content')
<h1>{{ $category['name'] }}</h1>
<p>{{ $category['description'] }}</p>

@foreach($contents as $content)
    <article>
        <h2>{{ $content->title }}</h2>

            <img src="{{ $content->image }}" alt="{{ $content->title }}">

        @if(isset($content->body))
            <p>{{ Str::limit($content->body, 150) }}</p>
        @endif
        <a href="{{ route('content.show', $content->slug) }}">Devamını oku</a>
    </article>
@endforeach

{{ $contents->links() }}
@endsection

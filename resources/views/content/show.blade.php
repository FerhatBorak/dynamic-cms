@extends('layouts.app')

@section('content')
    <article>
        <h1>{{ $content['title'] }}</h1>

        @if(get_content_field($content, 'image'))
            <img src="{{ get_content_field($content, 'image') }}" alt="{{ $content['title'] }}">
        @endif

        @if(get_content_field($content, 'content'))
            <div class="content">
                {!! get_content_field($content, 'content') !!}
            </div>
        @endif

        @if(get_content_field($content, 'author'))
            <p>{{ __('Author') }}: {{ get_content_field($content, 'author') }}</p>
        @endif

        <p>{{ __('Published Date') }}: {{ \Carbon\Carbon::parse($content['created_at'])->format('d.m.Y') }}</p>

        <a href="{{ route('category.show', $content['category']['slug']) }}">{{ __('Back to') }} {{ $content['category']['name'] }}</a>
    </article>
@endsection

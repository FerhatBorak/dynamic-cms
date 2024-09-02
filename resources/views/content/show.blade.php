@extends('layouts.app')

@section('content')
<h1>{{ $content->title }}</h1>

    <img src="{{ $content->image }}" alt="{{ $content->title }}">

<div>
    {!! $content->body !!}
</div>
<!-- Diğer alanlar için -->
@endsection

@extends('layouts.app')

@section('content')
    <h1>{{ $category['name'] }}</h1>

    @if($category['description'])
        <p>{{ $category['description'] }}</p>
    @endif

    <div class="category-contents">
        @foreach(get_category_items('blog') as $item)
        <h2>{{ $item->title }}</h2>

       <a href="{{ url($item->slug) }}">Devamını oku</a></
    @endforeach
    </div>

    @if($contents instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $contents->links() }}
    @endif
@endsection

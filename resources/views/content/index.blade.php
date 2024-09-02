@extends('layouts.app')

@section('title', $category['name'] . ' - Site Adı')

@section('content')
    <h1 class="mb-4">{{ $category['name'] }}</h1>

    <div class="row">

            <div class="col-md-4 mb-4">
                <div class="card">

                    @if(get_content_field($content, 'image'))
                        <img src="{{ get_content_field($content, 'image') }}" class="card-img-top" alt="{{ $content['title'] }}">
                    @endif
                    <div class="card-body">

                        <h5 class="card-title">{{ $content['title'] }}</h5>
                        @if(get_content_field($content, 'excerpt'))
                            <p class="card-text">{{ Str::limit(get_content_field($content, 'excerpt'), 100) }}</p>
                        @endif
                        <a href="{{ url($content['slug']) }}" class="btn btn-primary">Devamını Oku</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

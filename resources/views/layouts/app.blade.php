<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    <nav>
        <nav>
            <!-- Diğer menü öğeleri -->
            <div class="language-selector">
                @foreach(get_active_languages() as $lang)
                    <a href="{{ route('language.change', $lang->code) }}" @if(current_language()->code == $lang->code) class="active" @endif>
                        {{ $lang->name }}
                    </a>
                @endforeach
            </div>
        </nav>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Footer içeriği buraya gelebilir -->
    </footer>
</body>
</html>

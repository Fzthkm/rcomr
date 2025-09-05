<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RCOMR')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
</head>
@stack('scripts')
<body class="bg-gray-50 text-gray-900">
<nav class="bg-white/80 backdrop-blur border-b">
    <div class="container mx-auto px-6">
        <div class="flex h-14 items-center justify-between">
            {{-- Лого/бренд --}}
            <a href="/" class="text-xl font-bold text-indigo-600">RCOMR</a>

            {{-- Десктоп-меню --}}
            @php
                $nav = [
                  [
                    'label' => 'Заявки',
                    'route' => 'applications.index',
                    'match' => 'applications.*',
                    'icon'  => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="1.8"/><path d="M8 7v-.5A2.5 2.5 0 0 1 10.5 4h3A2.5 2.5 0 0 1 16 6.5V7" stroke="currentColor" stroke-width="1.8"/></svg>',
                  ],
                  [
                    'label' => 'Специалисты',
                    'route' => 'specialists.index',
                    'match' => 'specialists.*',
                    'icon'  => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.8"/><path d="M20 9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.8"/><path d="M3 18a5 5 0 0 1 10 0v2H3v-2Zm12 2v-2a5 5 0 0 1 7-4" stroke="currentColor" stroke-width="1.8"/></svg>',
                  ],
                ];
            @endphp

            <div class="hidden md:flex items-center gap-2">
                @foreach ($nav as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition
                    {{ $active
                        ? 'text-indigo-700 bg-indigo-50 ring-1 ring-indigo-100'
                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        {!! $item['icon'] !!}
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</nav>


<main class="container mx-auto">
    @yield('content')
</main>
</body>
</html>

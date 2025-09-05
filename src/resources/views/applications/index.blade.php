@extends('layouts.app')

@section('content')
    <div class="mx-auto py-8 px-4">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold">Заявки</h1>

            <div class="flex gap-2" x-data="{ open: false }" x-cloak>
                <div class="relative">
                    <!--<button @click="open = !open"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">
                        Фильтр
                    </button> -->

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition
                         class="mt-2 bg-white shadow-lg border rounded p-4 absolute z-50 w-80">
                        <form method="GET" action="{{ route('applications.index') }}" class="space-y-3">
                            <input type="text" name="application_number" placeholder="Номер заявки"
                                   value="{{ request('application_number') }}"
                                   class="w-full border px-3 py-2 rounded"/>

                            <input type="text" name="patient_name" placeholder="Имя пациента"
                                   value="{{ request('patient_name') }}"
                                   class="w-full border px-3 py-2 rounded"/>

                            <select name="status" class="w-full border px-3 py-2 rounded">
                                <option value="">Все статусы</option>
                                <option value="created"  @selected(request('status') === 'created')>Создана</option>
                                <option value="canceled" @selected(request('status') === 'canceled')>Отменена</option>
                            </select>

                            <div class="flex gap-2">
                                <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded w-full hover:bg-green-700">
                                    Применить
                                </button>
                                <a href="{{ route('applications.index') }}"
                                   class="bg-gray-100 px-4 py-2 rounded border w-full text-center hover:bg-gray-200">
                                    Сброс
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="{{ route('applications.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Создать заявку
                </a>
            </div>
        </div>

        {{-- Таблица заявок --}}
        <div class="bg-white shadow rounded overflow-x-auto">
            @if($paginated->count())
                <table class="min-w-full table-auto text-sm">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-3">№</th>
                        <th class="p-3">Дата</th>
                        <th class="p-3">Пациент</th>
                        <th class="p-3">Диагноз</th>
                        <th class="p-3">Учреждение (исполнитель)</th>
                        <th class="p-3">Учреждение (заявитель)</th>
                        <th class="p-3">Специалист</th>
                        <th class="p-3">Статус</th>
                        <th class="p-3">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($paginated as $application)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3">{{ $application['number'] ?? '—' }}</td>

                            <td class="p-3">
                                @php $cd = $application['consultation_date'] ?? null; @endphp
                                <time datetime="{{ $cd['iso'] ?? '' }}">{{ $cd['human'] ?? '—' }}</time>
                            </td>

                            <td class="p-3">{{ $application['patient']['name'] ?? '—' }}</td>

                            <td class="p-3">
                                {{ $application['diagnosis']['name'] ?? '—' }}
                            </td>

                            <td class="p-3">
                                {{ $application['institution']['from']['name'] ?? '—' }}
                            </td>

                            <td class="p-3">
                                {{ $application['institution']['to']['name'] ?? '—' }}
                            </td>

                            <td class="p-3">
                                {{ $application['specialist']['name'] ?? '—' }}
                            </td>

                            <td class="p-3">
                                @php
                                    $label = $application['status']['label'] ?? 'Неизвестно';
                                    $code  = $application['status']['code'] ?? null;
                                    $ok    = in_array($code, ['created', 1], true);
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    {{ $ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td class="flex p-3">
                                <a href="{{ route('applications.edit', $application['id']) }}"
                                   class="text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                                    <svg fill="#000000" width="16px" height="16px" viewBox="0 0 32 32">
                                        <path d="M30.133 1.552c-1.090-1.044-2.291-1.573-3.574-1.573-2.006 0-3.47 1.296-3.87 1.693-0.564 0.558-19.786 19.788-19.786 19.788-0.126 0.126-0.217 0.284-0.264 0.456-0.433 1.602-2.605 8.71-2.627 8.782-0.112 0.364-0.012 0.761 0.256 1.029 0.193 0.192 0.45 0.295 0.713 0.295 0.104 0 0.208-0.016 0.31-0.049 0.073-0.024 7.41-2.395 8.618-2.756 0.159-0.048 0.305-0.134 0.423-0.251 0.763-0.754 18.691-18.483 19.881-19.712 1.231-1.268 1.843-2.59 1.819-3.925-0.025-1.319-0.664-2.589-1.901-3.776zM22.37 4.87c0.509 0.123 1.711 0.527 2.938 1.765 1.24 1.251 1.575 2.681 1.638 3.007-3.932 3.912-12.983 12.867-16.551 16.396-0.329-0.767-0.862-1.692-1.719-2.555-1.046-1.054-2.111-1.649-2.932-1.984 3.531-3.532 12.753-12.757 16.625-16.628zM4.387 23.186c0.55 0.146 1.691 0.57 2.854 1.742 0.896 0.904 1.319 1.9 1.509 2.508-1.39 0.447-4.434 1.497-6.367 2.121 0.573-1.886 1.541-4.822 2.004-6.371zM28.763 7.824c-0.041 0.042-0.109 0.11-0.19 0.192-0.316-0.814-0.87-1.86-1.831-2.828-0.981-0.989-1.976-1.572-2.773-1.917 0.068-0.067 0.12-0.12 0.141-0.14 0.114-0.113 1.153-1.106 2.447-1.106 0.745 0 1.477 0.34 2.175 1.010 0.828 0.795 1.256 1.579 1.27 2.331 0.014 0.768-0.404 1.595-1.24 2.458z"></path>
                                    </svg>
                                </a>
                                @if(!empty($application['links']['duplicate']))
                                    <a href="{{ $application['links']['duplicate'] }}"
                                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                                        <svg fill="#000000" width="16px" height="16px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 1919.887h1467.88V452.008H0v1467.88ZM1354.965 564.922v1242.051H112.914V564.922h1242.051ZM1920 0v1467.992h-338.741v-113.027h225.827V112.914H565.035V338.74H452.008V0H1920ZM338.741 1016.93h790.397V904.016H338.74v112.914Zm0 451.062h790.397v-113.027H338.74v113.027Zm0-225.588h564.57v-112.913H338.74v112.913Z" fill-rule="evenodd"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="Нет ссылки">Дублировать</span>
                                @endif
                                <form action="{{ route('applications.destroy', $application['id']) }}"
                                      method="POST"
                                      onsubmit="return confirm('Точно удалить заявку?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded bg-red-600 transition">
                                        <svg width="16px" height="16px" viewBox="0 0 24 24">
                                            <path d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500">Заявки не найдены</div>
            @endif
        </div>

        <div class="mt-6">
            {{-- сохраняем фильтры при перелистывании --}}
            {{ $paginated->appends(request()->except('page'))->links() }}
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <style>[x-cloak]{ display:none !important; }</style>
@endsection

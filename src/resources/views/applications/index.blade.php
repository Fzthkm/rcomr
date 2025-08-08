@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold">Заявки</h1>
            <div class="flex gap-2">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">
                        Фильтр
                    </button>

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition
                         class="mt-2 bg-white shadow-lg border rounded p-4 absolute z-50 w-80">
                        <form method="GET" action="{{ route('applications.index') }}" class="space-y-3">
                            <input type="text" name="application_number" placeholder="Номер заявки"
                                   class="w-full border px-3 py-2 rounded"/>
                            <input type="text" name="patient_name" placeholder="Имя пациента"
                                   class="w-full border px-3 py-2 rounded"/>
                            <select name="status" class="w-full border px-3 py-2 rounded">
                                <option value="">Все статусы</option>
                                <option value="created">Создана</option>
                                <option value="canceled">Отменена</option>
                            </select>
                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded w-full hover:bg-green-700">
                                Применить
                            </button>
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
            @if(count($applications))
                <table class="min-w-full table-auto text-sm">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-3">№</th>
                        <th class="p-3">Дата</th>
                        <th class="p-3">Пациент</th>
                        <th class="p-3">Диагноз</th>
                        <th class="p-3">Учреждение (отправитель)</th>
                        <th class="p-3">Учреждение (получатель)</th>
                        <th class="p-3">Специалист</th>
                        <th class="p-3">Статус</th>
                        <th class="p-3">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $application)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3">{{ $application['application_number'] }}</td>
                            <td class="p-3">{{ $application['consultation_date'] }}</td>
                            <td class="p-3">{{ $application['patient_name'] }}</td>
                            <td class="p-3">{{ $application['diagnosis'] }}</td>
                            <td class="p-3">{{ $application['from_institution'] }}</td>
                            <td class="p-3">{{ $application['institution'] }}</td>
                            <td class="p-3">{{ $application['specialist'] }}</td>
                            <td class="p-3">{{ $application['status'] }}</td>
                            <td class="p-3 space-x-2">
                                <a href="{{ route('applications.create', Arr::only($application, [
                                    'application_number',
                                    'consultation_date',
                                    'institution_id',
                                    'specialist_id',
                                    'patient_name',
                                    'patient_year',
                                    'diagnosis_id',
                                ])) }}"
                                   class="text-indigo-600 hover:underline">
                                    Дублировать
                                </a>
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
            {!! $pagination !!}
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
@endsection

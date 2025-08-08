@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6">Создание заявки</h1>

        <form method="POST" action="{{ route('applications.store') }}" class="space-y-6 bg-white shadow p-6 rounded">
            @csrf
            {{-- Дата консультации --}}
            <div>
                <label for="consultation_date" class="block font-semibold mb-1">Дата консультации</label>
                <input type="text" name="consultation_date" id="consultation_date"
                       value="{{ old('consultation_date', $prefill['consultation_date'] ?? '') }}"
                       class="w-full border px-3 py-2 rounded flatpickr-date" required>
            </div>

            {{-- Имя пациента --}}
            <div>
                <label for="patient_name" class="block font-semibold mb-1">Имя пациента</label>
                <input type="text" name="patient_name" id="patient_name"
                       value="{{ old('patient_name', $prefill['patient_name'] ?? '') }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>

            {{-- Год рождения пациента --}}
            <div>
                <label for="patient_year" class="block font-semibold mb-1">Год рождения пациента</label>
                <input type="text" name="patient_year" id="patient_year"
                       value="{{ old('patient_year', $prefill['patient_year'] ?? '') }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>

            {{-- Учреждение --}}
            <div>
                <label for="institution_id" class="block font-semibold mb-1">Учреждение</label>
                <select name="institution_id" id="institution_id" class="js-select w-full" required>
                    <option value="">Выберите учреждение</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ old('institution_id', $prefill['institution_id'] ?? '') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Специалист --}}
            <div>
                <label for="specialist_id" class="block font-semibold mb-1">Специалист</label>
                <select name="specialist_id" id="specialist_id" class="js-select w-full" required>
                    <option value="">Выберите специалиста</option>
                    @foreach ($specialists as $specialist)
                        <option value="{{ $specialist->id }}"
                            {{ old('specialist_id', $prefill['specialist_id'] ?? '') == $specialist->id ? 'selected' : '' }}>
                            {{ $specialist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Диагноз --}}
            <div>
                <label for="diagnosis_id" class="block font-semibold mb-1">Диагноз</label>
                <select name="diagnosis_id" id="diagnosis_id" class="js-select w-full" required>
                    <option value="">Выберите диагноз</option>
                    @foreach($diagnoses as $diagnosis)
                        <option value="{{ $diagnosis->id }}"
                            {{ old('diagnosis_id', $prefill['diagnosis_id'] ?? '') == $diagnosis->id ? 'selected' : '' }}>
                            {{ $diagnosis->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Кнопка --}}
            <div class="pt-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Сохранить заявку
                </button>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-select').forEach(select => {
                new TomSelect(select, {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: 'Начните вводить...',
                });
            });

            flatpickr('.flatpickr-date', {
                dateFormat: 'd.m.Y',
                defaultDate: '{{ $prefill['consultation_date'] ?? now()->format('d.m.Y') }}',
            });
        });
    </script>

@endsection

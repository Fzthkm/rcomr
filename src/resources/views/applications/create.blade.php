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
                <label for="patient_birth_year" class="block font-semibold mb-1">Год рождения пациента</label>
                <input type="text" name="patient_birth_year" id="patient_birth_year"
                       value="{{ old('patient_birth_year', $prefill['patient_birth_year'] ?? '') }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>

            {{-- Учреждение --}}
            <div>
                <label for="institution_id" class="block font-semibold mb-1">Учреждение</label>
                <select name="institution_id" id="institution_id" class="js-select w-full" data-selected="{{ old('institution_id', $prefill['institution_id'] ?? '') }}" required>
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
                <select name="specialist_id" id="specialist_id" class="js-select w-full">
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
                <select name="diagnosis_id" id="diagnosis_id" class="js-select w-full" data-selected="{{ old('diagnosis_id', $prefill['diagnosis_id'] ?? '') }}" required>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-select').forEach(select => {
                const ts = new TomSelect(select, {
                    create: false,
                    shouldSort: false,
                    placeholder: 'Начните вводить...',
                });

                const val = select.dataset.selected;
                if (val) {
                    // если вдруг нужной опции нет (редко, но бывает) — добавим
                    if (!ts.options[val]) {
                        const text = select.querySelector(`option[value="${val}"]`)?.text || `ID ${val}`;
                        ts.addOption({ value: val, text });
                    }
                    ts.setValue(String(val), true);
                }
            });

            flatpickr('.flatpickr-date', {
                locale: 'ru',
                dateFormat: 'd.m.Y',
                defaultDate: '{{ $prefill['consultation_date'] ?? now()->format('d.m.Y') }}',
            });
        });

    </script>

@endsection

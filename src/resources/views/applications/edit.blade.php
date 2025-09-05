@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6">Редактирование заявки №{{ $application->application_number }} от {{ $application->created_at->format('d.m.Y') }}</h1>

        <form method="POST" action="{{ route('applications.update', $application->id) }}" class="space-y-6 bg-white shadow p-6 rounded">
            @csrf
            @method('PUT')

            <div>
                <label for="application_number" class="block font-semibold mb-1">Номер заявки</label>
                <input type="text" name="application_number" id="application_number"
                       value="{{ old('application_number', $application->application_number) }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>
            {{-- Дата консультации --}}
            <div>
                <label for="consultation_date" class="block font-semibold mb-1">Дата консультации</label>
                <input type="text" name="consultation_date" id="consultation_date"
                       value="{{ old('consultation_date', optional($application->consultation_date)->format('d.m.Y')) }}"
                       class="w-full border px-3 py-2 rounded flatpickr-date" required>
            </div>

            <div>
                <label for="created_at" class="block font-semibold mb-1">Дата создания записи</label>
                <input type="text" name="created_at" id="created_at"
                       value="{{ old('created_at', optional($application->created_at)->format('d.m.Y')) }}"
                       class="w-full border px-3 py-2 rounded flatpickr-date">
            </div>

            {{-- Имя пациента --}}
            <div>
                <label for="patient_name" class="block font-semibold mb-1">Имя пациента</label>
                <input type="text" name="patient_name" id="patient_name"
                       value="{{ old('patient_name', $application->patient_name) }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>

            {{-- Год рождения пациента --}}
            <div>
                <label for="patient_birth_year" class="block font-semibold mb-1">Год рождения пациента</label>
                <input type="text" name="patient_birth_year" id="patient_birth_year"
                       value="{{ old('patient_birth_year', $application->patient_birth_year) }}"
                       class="w-full border px-3 py-2 rounded" required>
            </div>

            {{-- Учреждение (куда направлена заявка) --}}
            <div>
                <label for="institution_id" class="block font-semibold mb-1">Учреждение-заявитель</label>
                <select name="institution_id" id="institution_id" class="js-select w-full"
                        data-selected="{{ old('institution_id', $application->institution_id) }}" required>
                    <option value="">Выберите учреждение</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Учреждение-источник (from_institution_id) --}}
            <div>
                <label for="from_institution_id" class="block font-semibold mb-1">Учреждение-исполнитель</label>
                <select name="from_institution_id" id="from_institution_id" class="js-select w-full"
                        data-selected="{{ old('from_institution_id', $application->from_institution_id) }}">
                    <option value="">Не указано</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Специалист --}}
            <div>
                <label for="specialist_id" class="block font-semibold mb-1">Специалист</label>
                <select name="specialist_id" id="specialist_id" class="js-select w-full"
                        data-selected="{{ old('specialist_id', $application->specialist_id) }}">
                    <option value="">Выберите специалиста</option>
                    @foreach ($specialists as $specialist)
                        <option value="{{ $specialist->id }}">
                            {{ $specialist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Диагноз --}}
            <div>
                <label for="diagnosis_id" class="block font-semibold mb-1">Диагноз</label>
                <select name="diagnosis_id" id="diagnosis_id" class="js-select w-full"
                        data-selected="{{ old('diagnosis_id', $application->diagnosis_id) }}" required>
                    <option value="">Выберите диагноз</option>
                    @foreach($diagnoses as $diagnosis)
                        <option value="{{ $diagnosis->id }}">
                            {{ $diagnosis->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Кнопки --}}
            <div class="pt-4 flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Сохранить изменения
                </button>
                <a href="{{ route('applications.index', $application) }}"
                   class="px-6 py-2 rounded border hover:bg-gray-50 transition">Отмена</a>
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
                    if (!ts.options[val]) {
                        const text = select.querySelector(`option[value="${val}"]`)?.text || `ID ${val}`;
                        ts.addOption({ value: val, text });
                    }
                    ts.setValue(String(val), true);
                }
            });

            flatpickr('.flatpickr-date', {
                locale: 'ru',
                dateFormat: 'd.m.Y'
            });
        });
    </script>
@endsection

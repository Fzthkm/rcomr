{{-- resources/views/specialists/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Изменить специалиста')

@section('content')
    <div class="container mx-auto max-w-3xl">
        <h1 class="text-2xl font-semibold mb-6">Изменить специалиста</h1>

        <form action="{{ route('specialists.update', $specialist->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-semibold mb-1">
                    ФИО специалиста <span class="text-red-600">*</span>
                </label>
                <input id="name" name="name"
                       class="w-full border rounded-md px-3 py-2"
                       value="{{ old('name', $specialist->name) }}"
                       required>
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="workplace_id" class="block font-semibold mb-1">
                    Учреждение <span class="text-red-600">*</span>
                </label>
                <select name="workplace_id" id="workplace_id"
                        class="js-select w-full border rounded-md"
                        data-selected="{{ old('workplace_id', $specialist->workplace_id) }}"
                        required>
                    <option value="">Выберите учреждение</option>
                    @foreach ($workplaces as $workplace)
                        <option value="{{ $workplace->id }}">
                            {{ $workplace->name }}
                        </option>
                    @endforeach
                </select>
                @error('workplace_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="specialization_id" class="block font-semibold mb-1">
                    Специализация <span class="text-red-600">*</span>
                </label>
                <select name="specialization_id" id="specialization_id"
                        class="js-select w-full border rounded-md"
                        data-selected="{{ old('specialization_id', $specialist->specialization_id) }}"
                        required>
                    <option value="">Выберите специализацию</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}">
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
                @error('specialization_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="education" class="block text-sm font-medium mb-1">Образование</label>
                <input id="education" name="education" type="text"
                       class="w-full border rounded-md px-3 py-2"
                       value="{{ old('education', $specialist->education) }}">
                @error('education') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="additional_info" class="block text-sm font-medium mb-1">Дополнительная информация</label>
                <input id="additional_info" name="additional_info"
                       class="w-full border rounded-md px-3 py-2"
                       value="{{ old('additional_info', $specialist->additional_info) }}">
                @error('additional_info') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 actions-bar">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition border">
                    Сохранить изменения
                </button>
                <a href="{{ route('specialists.index') }}" class="px-4 py-2 rounded-md border">Отмена</a>
            </div>
        </form>
    </div>

    {{-- тот же инициализатор, что и в create --}}
    <script>
        document.querySelectorAll('.js-select').forEach(select => {
            if (select.tomselect) select.tomselect.destroy();

            const ts = new TomSelect(select, {
                create: false,
                shouldSort: false,
                placeholder: 'Начните вводить...',
            });

            const val = select.dataset.selected;
            if (val) {
                if (!ts.options[val]) {
                    const opt = select.querySelector(`option[value="${val}"]`);
                    ts.addOption({ value: val, text: opt ? opt.text : `ID ${val}` });
                }
                ts.setValue(String(val), true);
            }
        });
    </script>
@endsection

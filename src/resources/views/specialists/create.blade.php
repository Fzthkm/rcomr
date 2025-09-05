{{-- resources/views/specialists/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Создать специалиста')

@section('content')
    <div class="container mx-auto max-w-3xl">
        <h1 class="text-2xl font-semibold mb-6">Создать специалиста</h1>

        <form action="{{ route('specialists.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block font-semibold mb-1">ФИО специалиста<span class="text-red-600">*</span></label>
                <input id="name" name="name" class="w-full border rounded-md px-3 py-2" required>
            </div>

            <div>
                <label for="workplace_id" class="block font-semibold mb-1">Учреждение<span class="text-red-600">*</span></label>
                <select name="workplace_id" id="workplace_id" class="js-select w-full border rounded-md" required>
                    <option value="">Выберите учреждение</option>
                    @foreach ($workplaces as $workplace)
                        <option value="{{ $workplace->id }}">
                            {{ $workplace->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="specialization_id" class="block font-semibold mb-1">Специализация<span class="text-red-600">*</span></label>
                <select name="specialization_id" id="specialization_id" class="js-select w-full border rounded-md" required>
                    <option value="">Выберите специализацию</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}">
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Education --}}
            <div>
                <label for="education" class="block text-sm font-medium mb-1">Образование</label>
                <input
                    type="text"
                    id="education"
                    name="education"
                    class="w-full border rounded-md px-3 py-2"
                />
                @error('education')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Additional info --}}
            <div>
                <label for="additional_info" class="block text-sm font-medium mb-1">Дополнительная информация</label>
                <input
                    id="additional_info"
                    name="additional_info"
                    class="w-full border rounded-md px-3 py-2"
                >
                @error('additional_info')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 actions-bar">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition border">Сохранить</button>
                <a href="{{ route('specialists.index') }}" class="px-4 py-2 rounded-md border">Отмена</a>
            </div>

        </form>
    </div>
    <script>
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
    </script>
@endsection

@php($isEdit = isset($specialist))

{{-- ФИО --}}
<div>
    <label for="name" class="block font-semibold mb-1">ФИО *</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', data_get($specialist ?? null, 'name', '')) }}"
           class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror"
           required>
    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Учреждение из справочника --}}
<div>
    <label for="workplace_id" class="block font-semibold mb-1">Учреждение (из справочника)</label>
    <select name="workplace_id" id="workplace_id"
            class="js-select w-full"
            data-selected="{{ old('workplace_id', data_get($specialist ?? null, 'workplace_id', '')) }}">
        <option value="">— не выбрано —</option>
        @foreach($institutions as $inst)
            <option value="{{ $inst->id }}">{{ $inst->name }}</option>
        @endforeach
    </select>
    @error('workplace_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Специализация --}}
<div>
    <label for="specialization_id" class="block font-semibold mb-1">Специализация *</label>
    <select name="specialization_id" id="specialization_id"
            class="js-select w-full @error('specialization_id') border-red-500 @enderror"
            data-selected="{{ old('specialization_id', data_get($specialist ?? null, 'specialization_id', '')) }}"
            required>
        <option value="">— выберите специализацию —</option>
        @foreach($specializations as $spec)
            <option value="{{ $spec->id }}">{{ $spec->name }}</option>
        @endforeach
    </select>
    @error('specialization_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Образование --}}
<div>
    <label for="education" class="block font-semibold mb-1">Образование</label>
    <input name="education" id="education"
              class="w-full border px-3 py-2 rounded @error('education') border-red-500 @enderror">{{ old('education', data_get($specialist ?? null, 'education', '')) }}</input>
    @error('education')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Доп. информация --}}
<div>
    <label for="additional_info" class="block font-semibold mb-1">Доп. информация</label>
    <input name="additional_info" id="additional_info"
              class="w-full border px-3 py-2 rounded @error('additional_info') border-red-500 @enderror">{{ old('additional_info', data_get($specialist ?? null, 'additional_info', '')) }}</input>
    @error('additional_info')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div class="pt-4 flex gap-3">
    <button type="submit"
            class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition">
        {{ $isEdit ? 'Сохранить изменения' : 'Создать специалиста' }}
    </button>
    <a href="{{ route('specialists.index') }}"
       class="px-3 py-2 rounded border hover:bg-gray-50">Отмена</a>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-select').forEach(select => {
                const ts = new TomSelect(select, {
                    create: false,
                    shouldSort: false,
                    placeholder: 'Начните вводить...'
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
        });
    </script>
@endpush

@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Add New Weapon</h2>

    <form method="POST" action="{{ route('weapons.store') }}">
        @csrf

        {{-- Serial Number --}}
        <div class="mb-3">
            <label for="serial_number" class="form-label">Serial Number</label>
            <input type="text" name="serial_number" id="serial_number" class="form-control" required>
        </div>

        {{-- Weapon Model --}}
        <div class="mb-3">
            <label for="weapon_model_id" class="form-label">Weapon Model</label>
            <select name="weapon_model_id" id="weapon_model_id" class="form-select" required>
                <option value="">-- Select Model --</option>
                @foreach($categories as $category)
                    @foreach($category->types as $type)
                        <optgroup label="{{ $category->name }} → {{ $type->name }}">
                            @foreach($type->models as $model)
                                <option value="{{ $model->id }}"
                                        data-type="{{ $type->name }}"
                                        data-category="{{ $category->name }}">
                                    {{ $model->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endforeach
            </select>
        </div>

        {{-- Auto-filled Preview --}}
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" id="category_preview" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Weapon Type</label>
            <input type="text" id="type_preview" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Save Weapon</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modelSelect = document.getElementById('weapon_model_id');
    const typePreview = document.getElementById('type_preview');
    const categoryPreview = document.getElementById('category_preview');

    modelSelect.addEventListener('change', function () {
        const selected = modelSelect.options[modelSelect.selectedIndex];
        typePreview.value = selected.getAttribute('data-type') || '';
        categoryPreview.value = selected.getAttribute('data-category') || '';
    });
});
</script>
@endsection

@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Edit Weapon</h2>

    <form method="POST" action="{{ route('weapons.update', $weapon) }}">
        @csrf
        @method('PUT')

        {{-- Serial Number --}}
        <div class="form-group mb-3">
            <label for="serial_number" class="fw-semibold">Serial Number</label>
            <input type="text" name="serial_number" class="form-control"
                   value="{{ old('serial_number', $weapon->serial_number) }}" required>
        </div>

        {{-- Weapon Model --}}
        <div class="form-group mb-3">
            <label for="weapon_model_id" class="fw-semibold">Weapon Model</label>
            <select name="weapon_model_id" id="weapon_model_id" class="form-control select2" required>
                <option value="">-- Select Model --</option>
                @foreach($models as $model)
                    <option value="{{ $model->id }}"
                        data-type="{{ $model->type->name }}"
                        data-category="{{ $model->category->name }}"
                        {{ $weapon->weapon_model_id == $model->id ? 'selected' : '' }}>
                        {{ $model->name }} ({{ $model->type->name }} - {{ $model->category->name }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Auto-filled Type --}}
        <div class="form-group mb-3">
            <label class="fw-semibold">Weapon Type</label>
            <input type="text" id="weapon_type" class="form-control"
                   value="{{ $weapon->model->type->name ?? '' }}" readonly>
        </div>

        {{-- Auto-filled Category --}}
        <div class="form-group mb-3">
            <label class="fw-semibold">Category</label>
            <input type="text" id="category" class="form-control"
                   value="{{ $weapon->model->category->name ?? '' }}" readonly>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>

{{-- Auto-fill script --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modelSelect = document.getElementById('weapon_model_id');
    const typeInput   = document.getElementById('weapon_type');
    const catInput    = document.getElementById('category');

    function updateFields() {
        const selected = modelSelect.options[modelSelect.selectedIndex];
        typeInput.value = selected.getAttribute('data-type') || '';
        catInput.value  = selected.getAttribute('data-category') || '';
    }

    modelSelect.addEventListener('change', updateFields);
    updateFields(); 
});
</script>
@endsection

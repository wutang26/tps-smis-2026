@extends('layouts.main')

@section('content')
@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Weapons</a></li>
                <li class="breadcrumb-item active"><a href="#">Models</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection
<div class="card mb-3">
    <div class="container">
        <h2>Weapon Models</h2>

        {{-- ADD NEW MODEL BUTTON --}}
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addWeaponModelModal">
            Add New Model
        </button>

        {{-- ADD MODEL MODAL --}}
        <div class="modal fade" id="addWeaponModelModal" tabindex="-1" aria-labelledby="addWeaponModelModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Weapon Model</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('weapon-models.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            {{-- MODEL NAME --}}
                            <div class="mb-3">
                                <label for="addModelName" class="form-label">Model Name</label>
                                <input type="text" name="name" id="addModelName" class="form-control" required placeholder="Enter weapon model name">
                            </div>
                                                        <div class="mb-3">
                                <label for="modelDescription" class="form-label">Model description</label>
                                <textarea name="description" id="modelDescription" class="form-control" required></textarea>
                            </div>      
                            {{-- WEAPON TYPE --}}
                            <div class="mb-3">
                                <label for="addWeaponType" class="form-label">Weapon Type</label>
                                <select name="weapon_type_id" id="addWeaponType" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach($weaponTypes as $type)
                                        <option value="{{ $type->id }}" data-category="{{ $type->weapon_category_id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CATEGORY (always disabled) --}}
                            <div class="mb-3">
                                <label for="addWeaponCategory" class="form-label">Weapon Category</label>
                                <select name="weapon_category_id" id="addWeaponCategory" class="form-control" required disabled>
                                    <option value="">-- Category will auto-fill --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Model</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- FILTER FORM --}}
        <form method="GET" action="{{ route('weapon-models.index') }}" class="row mb-3 align-items-center">
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">-- All Categories --</option>
                    <option value="Explosive" {{ request('category') == 'Explosive' ? 'selected' : '' }}>Explosive</option>
                    <option value="Ammunition" {{ request('category') == 'Ammunition' ? 'selected' : '' }}>Ammunition</option>
                    <option value="Firearm" {{ request('category') == 'Firearm' ? 'selected' : '' }}>Firearm</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Name" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('weapon-models.index') }}" class="btn btn-secondary">Reset</a>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('weapons.uploads') }}" class="btn btn-sm btn-success">Upload Weapons</a>
            </div>
        </form>

        {{-- MESSAGES --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- WEAPONS TABLE --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Counts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $model)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $model->name }}</td>
                        <td>{{ $model->type?->name }}</td>
                        <td>{{ $model->type?->category?->name }}</td>
                        <td>{{ $model->weapons->count() }}</td>
                        <td class="d-flex gap-2">
                            {{-- EDIT BUTTON --}}
                            <button 
                                class="btn btn-sm btn-warning edit-btn" 
                                data-id="{{ $model->id }}" 
                                data-name="{{ $model->name }}" 
                                data-desc = "{{ $model->description }}"
                                data-type="{{ $model->type?->id }}">
                                Edit
                            </button>

                            {{-- VIEW BUTTON --}}
                            <form action="{{ route('weapons.index') }}" method="get">
                                @csrf
                                <input type="hidden" name="model_id" value="{{ $model->id }}">
                                <button type="submit" class="btn btn-sm btn-info">View</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINATION --}}
        {!! $models->appends(request()->query())->links('pagination::bootstrap-5') !!}

        {{-- EDIT MODAL --}}
        <div class="modal fade" id="editWeaponModelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Weapon Model</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="editWeaponModelForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editModelName" class="form-label">Model Name</label>
                                <input type="text" name="name" id="editModelName" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="editModelDescription" class="form-label">Model description</label>
                                <textarea name="description" id="editModelDescription" class="form-control" required></textarea>
                            </div>                            

                            <div class="mb-3">
                                <label for="editWeaponType" class="form-label">Weapon Type</label>
                                <select name="weapon_type_id" id="editWeaponType" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach($weaponTypes as $type)
                                        <option value="{{ $type->id }}" data-category="{{ $type->weapon_category_id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="editWeaponCategory" class="form-label">Weapon Category</label>
                                <select name="weapon_category_id" id="editWeaponCategory" class="form-control" required disabled>
                                    <option value="">-- Category will auto-fill --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Model</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ADD MODAL CATEGORY AUTO-FILL (always disabled)
    const addTypeSelect = document.getElementById('addWeaponType');
    const addCategorySelect = document.getElementById('addWeaponCategory');

    function updateAddCategory() {
        const selectedType = addTypeSelect.options[addTypeSelect.selectedIndex];
        const categoryId = selectedType ? selectedType.getAttribute('data-category') : null;
        addCategorySelect.value = categoryId || "";
        addCategorySelect.disabled = true; // always disabled
    }
    addTypeSelect.addEventListener('change', updateAddCategory);

    // EDIT MODAL CATEGORY AUTO-FILL (always disabled)
    const editModal = new bootstrap.Modal(document.getElementById('editWeaponModelModal'));
    const editForm = document.getElementById('editWeaponModelForm');
    const editNameInput = document.getElementById('editModelName');
    const editDescriptionInput = document.getElementById('editModelDescription');
    const editTypeSelect = document.getElementById('editWeaponType');
    const editCategorySelect = document.getElementById('editWeaponCategory');

    function updateEditCategory() {
        const selectedType = editTypeSelect.options[editTypeSelect.selectedIndex];
        const categoryId = selectedType ? selectedType.getAttribute('data-category') : null;
        editCategorySelect.value = categoryId || "";
        editCategorySelect.disabled = true; // always disabled
    }
    editTypeSelect.addEventListener('change', updateEditCategory);

    // OPEN EDIT MODAL AND FILL DATA
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const modelId = this.dataset.id;
            const name = this.dataset.name;
            const typeId = this.dataset.type;
            const desc = this.dataset.desc;
            // SET FORM ACTION DYNAMICALLY
            editForm.action = "{{ url('weapon-models') }}/" + modelId;

            // FILL FORM FIELDS
            editNameInput.value = name;
            editDescriptionInput.value = desc;
            editTypeSelect.value = typeId;

            updateEditCategory();
            editModal.show();
        });
    });
});
</script>
@endsection

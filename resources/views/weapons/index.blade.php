@extends('layouts.main')

@section('content')
    @section('scrumb')
        <nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Weapons</a></li>
                    </ol>
                </nav>
            </div>
        </nav>
    @endsection
    <div class="card mb-3">
        <div class="container">
            <h2>
                {{$model?->name}}
            </h2>

            <!-- Add Weapon Button -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#weaponModal">
                Add New
            </button>

            {{-- Filter Form --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <!-- FILTER FORM -->
                <form class="d-flex gap-2" method="GET" action="{{ route('weapons.index') }}"
                    class="d-flex gap-2 align-items-center flex-wrap">
                    @if ($weapons->count() > 0)
                        <input type="hidden" name="model_id" value="{{ $weapons[0]->weaponModel_id }}">
                    @endif
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">-- Status --</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="taken" {{ request('status') == 'taken' ? 'selected' : '' }}>Taken</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <input type="text" name="search" class="form-control" placeholder="Search by Serial"
                        value="{{ request('search') }}">

                    <!-- Filter Button -->
                    <button type="submit" @if ($weapons->count() == 0) disabled @endif class="btn btn-primary">
                        Filter
                    </button>

                    <!-- Reset Button -->

                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>

                <!-- UPLOAD BUTTON -->
                <div class="text-end mt-2 mt-md-0">
                    <a href="{{ route('weapons.uploads') }}" class="btn btn-sm btn-success">Upload Weapons</a>
                </div>
            </div>

            {{-- Messages --}}
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

            @if ($weapons->isEmpty())
                <h3>No weapons for {{ $model?->name }}</h3>
            @else
                {{-- Weapons Table --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Weapon Type</th>
                            <th>Category</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weapons as $weapon)
                            @php
                                $owner = [];
                                if (!empty($weapon->owner)) {
                                    if (is_string($weapon->owner)) {
                                        $decoded = json_decode($weapon->owner, true);
                                        if (is_array($decoded))
                                            $owner = $decoded;
                                    } elseif (is_array($weapon->owner)) {
                                        $owner = $weapon->owner;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $weapon->serial_number }}</td>
                                <td>{{ $weapon->weaponModel->type->name ?? 'N/A' }}</td>
                                <td>{{ $weapon->weaponModel->type->category->name ?? 'N/A' }}</td>
                                <td>{{ $weapon->ownershipType?->name }}</td>
                                <td>
                                    @if($weapon->status === 'available')
                                        <span class="badge bg-success">{{ ucfirst($weapon->status) }}</span>
                                    @elseif($weapon->status === 'taven')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($weapon->status) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($weapon->status) }}</span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit-weapon-btn" data-id="{{ $weapon->id }}"
                                        data-serial="{{ $weapon->serial_number }}"
                                        data-ownership="{{ $weapon->weaponOwnershipType_id }}"
                                        data-company="{{ $weapon->company_id }}" data-owner-name="{{ $owner['name'] ?? '' }}"
                                        data-owner-phone="{{ $owner['phone'] ?? '' }}" data-owner-nin="{{ $owner['nin'] ?? '' }}"
                                        data-model-id="{{ $weapon->weaponModel_id }}"
                                        data-category="{{ $weapon->weaponModel->type->category->name ?? '' }}"
                                        data-type="{{ $weapon->weaponModel->type->name ?? '' }}">
                                        Edit
                                    </button>
                                    <a href="{{ route('weapons.show', $weapon) }}" class="btn btn-info btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            @endif
            {{ $weapons->links() }}
        </div>

        <!-- Statistics Modal -->
        <div class="modal fade" id="statisticsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">📊 Weapon Statistics (Filtered Results)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Total Weapons Found:</strong>
                            {{ $totalWeapons ?? $weapons->total() ?? $weapons->count() }}</p>
                        <hr>
                        <h6>By Category:</h6>
                        @if(!empty($categoryCounts) && is_array($categoryCounts))
                            <ul>
                                @foreach($categoryCounts as $cat => $count)
                                    <li>{{ $cat }}: {{ $count }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No category breakdown available.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Weapon Modal -->
        <div class="modal fade" id="weaponModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('weapons.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Weapon </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Serial Number -->
                            <div class="mb-3">
                                <label for="serial_number" class="form-label">Serial Number</label>
                                <input type="text" name="serial_number" id="serial_number" class="form-control" required>
                            </div>

                            <!-- Ownership -->
                            <div class="mb-3">
                                <label for="weapon_ownership_type_id" class="form-label">Ownership Type</label>
                                <select name="weapon_ownership_type_id" id="weapon_ownership_type_id" class="form-select"
                                    required>
                                    <option value="">-- Select Ownership Type --</option>
                                    @foreach($ownershipTypes as $ownershipType)
                                        <option value="{{ $ownershipType->id }}">{{ $ownershipType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Company -->
                            <div id="company_select_container" class="mb-3" style="display:none;">
                                <label for="company_id" class="form-label">Company</label>
                                <select name="company_id" id="company_id" class="form-select">
                                    <option value="">-- Select Company --</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Owner -->
                            <div id="owner_details_container" style="display:none;">
                                <h5>Owner Details</h5>
                                <div class="mb-3">
                                    <label for="owner_name" class="form-label">Name</label>
                                    <input type="text" name="owner_name" id="owner_name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="owner_phone" class="form-label">Phone</label>
                                    <input type="tel" name="owner_phone" id="owner_phone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="owner_nin" class="form-label">NIN</label>
                                    <input type="text" name="owner_nin" id="owner_nin" class="form-control">
                                </div>
                            </div>

                            <!-- Weapon Model -->

                        </div>
                        <input type="text" name="weaponModel_id" value="{{ $model->id }}" hidden>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Weapon</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Weapon Modal -->
        <div class="modal fade" id="editWeaponModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" id="editWeaponForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Weapon</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Serial Number -->
                            <div class="mb-3">
                                <label for="edit_serial_number" class="form-label">Serial Number</label>
                                <input type="text" name="serial_number" id="edit_serial_number" class="form-control"
                                    required>
                            </div>

                            <!-- Ownership -->
                            <div class="mb-3">
                                <label for="edit_weapon_ownership_type_id" class="form-label">Ownership Type</label>
                                <select name="weapon_ownership_type_id" id="edit_weapon_ownership_type_id"
                                    class="form-select" required>
                                    <option value="">-- Select Ownership Type --</option>
                                    @foreach($ownershipTypes as $ownershipType)
                                        <option value="{{ $ownershipType->id }}">{{ $ownershipType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Company -->
                            <div id="edit_company_select_container" class="mb-3" style="display:none;">
                                <label for="edit_company_id" class="form-label">Company</label>
                                <select name="company_id" id="edit_company_id" class="form-select">
                                    <option value="">-- Select Company --</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Owner -->
                            <div id="edit_owner_details_container" style="display:none;">
                                <h5>Owner Details</h5>
                                <div class="mb-3">
                                    <label for="edit_owner_name" class="form-label">Name</label>
                                    <input type="text" name="owner_name" id="edit_owner_name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_owner_phone" class="form-label">Phone</label>
                                    <input type="tel" name="owner_phone" id="edit_owner_phone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_owner_nin" class="form-label">NIN</label>
                                    <input type="text" name="owner_nin" id="edit_owner_nin" class="form-control">
                                </div>
                            </div>

                            <!-- Weapon Model -->
                            <div class="mb-3">
                                <label for="edit_weapon_model_id" class="form-label">Weapon Model</label>
                                <select name="weaponModel_id" id="edit_weapon_model_id" class="form-select" required>
                                    <option value="">-- Select Model --</option>
                                    @foreach($categories as $category)
                                        @foreach($category->groupedModelsForSelect() as $typeName => $models)
                                            <optgroup label="{{ $category->name }} → {{ $typeName }}">
                                                @foreach($models as $model)
                                                    <option value="{{ $model->id }}" data-type="{{ $typeName }}"
                                                        data-category="{{ $category->name }}">
                                                        {{ $model->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <!-- Previews -->
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" id="edit_category_preview" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weapon Type</label>
                                <input type="text" id="edit_type_preview" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Weapon</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- JS --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Add modal ---
                const qs = (s, r = document) => r.querySelector(s);
                const qsa = (s, r = document) => Array.from(r.querySelectorAll(s));

                const modelSelect = qs('#weapon_model_id');
                const typePreview = qs('#type_preview');
                const categoryPreview = qs('#category_preview');

                if (modelSelect) {
                    modelSelect.addEventListener('change', () => {
                        const sel = modelSelect.options[modelSelect.selectedIndex];
                        typePreview.value = sel?.dataset.type || '';
                        categoryPreview.value = sel?.dataset.category || '';
                    });
                }

                const ownershipSelect = qs('#weapon_ownership_type_id');
                const ownerContainer = qs('#owner_details_container');
                const companyContainer = qs('#company_select_container');

                function toggleAddOwnership() {
                    const val = ownershipSelect.value;
                    if (val === '1') {
                        companyContainer.style.display = 'block';
                        ownerContainer.style.display = 'none';
                        qs('#owner_name').value = '';
                        qs('#owner_phone').value = '';
                        qs('#owner_nin').value = '';
                    } else if (val) {
                        companyContainer.style.display = 'none';
                        ownerContainer.style.display = 'block';
                        qs('#company_id').value = '';
                    } else {
                        companyContainer.style.display = 'none';
                        ownerContainer.style.display = 'none';
                        qs('#company_id').value = '';
                        qs('#owner_name').value = '';
                        qs('#owner_phone').value = '';
                        qs('#owner_nin').value = '';
                    }
                }
                ownershipSelect.addEventListener('change', toggleAddOwnership);
                toggleAddOwnership();

                // --- Edit modal ---
                const editModal = qs('#editWeaponModal');
                const editForm = qs('#editWeaponForm');
                const editSerial = qs('#edit_serial_number');
                const editOwnership = qs('#edit_weapon_ownership_type_id');
                const editOwnerContainer = qs('#edit_owner_details_container');
                const editCompanyContainer = qs('#edit_company_select_container');
                const editCompany = qs('#edit_company_id');
                const editOwnerName = qs('#edit_owner_name');
                const editOwnerPhone = qs('#edit_owner_phone');
                const editOwnerNin = qs('#edit_owner_nin');
                const editModel = qs('#edit_weapon_model_id');
                const editTypePreview = qs('#edit_type_preview');
                const editCategoryPreview = qs('#edit_category_preview');
                const updateTemplate = "{{ route('weapons.update', 0) }}";

                function toggleEditOwnership() {
                    const val = String(editOwnership.value);
                    if (val === '1') {
                        editCompanyContainer.style.display = 'block';
                        editOwnerContainer.style.display = 'none';
                        editOwnerName.value = '';
                        editOwnerPhone.value = '';
                        editOwnerNin.value = '';
                    } else if (val) {
                        editCompanyContainer.style.display = 'none';
                        editOwnerContainer.style.display = 'block';
                        editCompany.value = '';
                    } else {
                        editCompanyContainer.style.display = 'none';
                        editOwnerContainer.style.display = 'none';
                        editCompany.value = '';
                        editOwnerName.value = '';
                        editOwnerPhone.value = '';
                        editOwnerNin.value = '';
                    }
                }
                editOwnership.addEventListener('change', toggleEditOwnership);

                qsa('.edit-weapon-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        editSerial.value = btn.dataset.serial;
                        editOwnership.value = String(btn.dataset.ownership || '');
                        toggleEditOwnership();
                        editCompany.value = btn.dataset.company || '';
                        editOwnerName.value = btn.dataset.ownerName || '';
                        editOwnerPhone.value = btn.dataset.ownerPhone || '';
                        editOwnerNin.value = btn.dataset.ownerNin || '';
                        editModel.value = btn.dataset.modelId || '';
                        editTypePreview.value = btn.dataset.type || '';
                        editCategoryPreview.value = btn.dataset.category || '';
                        editForm.action = updateTemplate.replace('/0', '/' + id);
                        new bootstrap.Modal(editModal).show();
                    });
                });

                editModel.addEventListener('change', () => {
                    const sel = editModel.options[editModel.selectedIndex];
                    editTypePreview.value = sel?.dataset.type || '';
                    editCategoryPreview.value = sel?.dataset.category || '';
                });
            });
        </script>
    </div>
@endsection
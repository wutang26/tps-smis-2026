@extends('layouts.main')

@section('content')
    @section('scrumb')
        <nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                        <li class="breadcrumb-item "><a href="#">Borrows</a></li>
                        <li class="breadcrumb-item active"><a href="#">Add weapons</a></li>
                    </ol>
                </nav>
            </div>
        </nav>
    @endsection

    <div class="container">
        <h2> Add Weapons</h2>
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('borrowed-weapons.index') }}" class="row mb-3">
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">-- All Categories --</option>
                    <option value="Explosive" {{ request('category') == 'Explosive' ? 'selected' : '' }}>Explosive</option>
                    <option value="Ammunition" {{ request('category') == 'Ammunition' ? 'selected' : '' }}>Ammunition</option>
                    <option value="Firearm" {{ request('category') == 'Firearm' ? 'selected' : '' }}>Firearm</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by Serial/Model" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Bulk Action Form --}}
        <form method="POST" action="{{ route('borrowed-weapons.store') }}">
            @csrf

            {{-- Weapons Table --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>#</th>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Weapon Type</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weapons as $weapon)
                        <tr>
                            <td>
                                <input type="checkbox" name="weapon_ids[]" value="{{ $weapon->id }}" class="weapon-checkbox">
                            </td>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $weapon->serial_number }}</td>
                            <td>{{ $weapon->weaponModel->name ?? 'N/A' }}</td>
                            <td>{{ $weapon->weaponModel->type->name ?? 'N/A' }}</td>
                            <td>{{ $weapon->weaponModel->type->category->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No weapons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <input type="text" name="weapon_borrower_id" value="{{ $weapon_borrower_id }}" hidden>
            <div class="d-flex justify-content-end mb-2">
                <button type="submit" class="btn btn-primary mt-2">Add</button>
            </div>
                
        </form>
    </div>

{{-- Select All Checkbox Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.weapon-checkbox');

        // Toggle all checkboxes when "Select All" is clicked
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        // Update "Select All" if any individual checkbox is changed
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                // If any checkbox is unchecked, uncheck Select All
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                    // If all checkboxes are checked, check Select All
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                }
            });
        });
    });
</script>

@endsection

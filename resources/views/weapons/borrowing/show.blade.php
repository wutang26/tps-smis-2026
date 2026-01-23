@extends('layouts.main')

@section('content')
    @section('scrumb')
        <nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                        <li class="breadcrumb-item "><a href="#">Borrows</a></li>
                        <li class="breadcrumb-item "><a href="#">Weapons</a></li>
                        <li class="breadcrumb-item "><a href="#">{{ $model }}</a></li>
                    </ol>
                </nav>
            </div>
        </nav>
    @endsection
<div class="mb-3">

    @if($weaponsOfModel->isNotEmpty())
        <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Weapon Type</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weaponsOfModel as $bw)
                        
                            @php
                            $weapon = $bw->weapon;

                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $weapon->serial_number }}</td>
                                <td>{{ $weapon->weaponModel->type->name ?? 'N/A' }}</td>
                                <td>{{ $weapon->weaponModel->type->category->name ?? 'N/A' }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>


                </table>
    @else
        <p>No weapons of this model.</p>
    @endif

    <a href="{{ route('borrowed-weapons.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

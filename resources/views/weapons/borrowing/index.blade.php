@extends('layouts.main')

@section('content')
    @section('scrumb')
        <nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Borrows</a></li>
                    </ol>
                </nav>
            </div>
        </nav>
    @endsection

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex gap-2 mb-2 justify-content-end">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#borrowerModal">
            Add Borrower
        </button>
    </div>
    <div class="modal fade" id="borrowerModal" tabindex="-1" aria-labelledby="borrowerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowerModalLabel">Add Borrower</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="borrowerForm" method="post" action="{{ route('weapon-borrowing.store') }}">
                    @csrf
                    <div class="modal-body">


                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Receiver Name</label>
                            <input type="text" class="form-control" name="received_officer_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Receiver Phone</label>
                            <input type="number" class="form-control" name="received_officer_phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>

                        <div class="mb-3">
                            <label class="for   m-label">Expected Return Date</label>
                            <input type="date" class="form-control" name="expected_return_date">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Borrower</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($weapon_borrowers->isEmpty())
        <h3>No weapon borrower found</h3>
    @else

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Armorer</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>Expected Return Date</th>
                    <th>Returned at</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weapon_borrowers as $weapon_borrower)
    <tr>
        <td>{{ $loop->iteration }}.</td>
        <td>{{ $weapon_borrower->armorer->name }}</td>
        <td>{{ $weapon_borrower->name ?? '' }}</td>
        <td>{{ $weapon_borrower->start_date }}</td>
        <td>{{ $weapon_borrower->expected_return_date }}</td>
        <td>{{ $weapon_borrower->returned_at ?? '' }} </td>
        <td>
            @switch($weapon_borrower->status)
                @case('pending')
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-hourglass-split"></i> Pending
                    </span>
                    @break
                @case('approved')
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Approved
                    </span>
                    @break
                @case('rejected')
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle"></i> Rejected
                    </span>
                    @break
                @case('returned')
                    <span class="badge bg-primary">
                        <i class="bi bi-arrow-counterclockwise"></i> Returned
                    </span>
                    @break
            @endswitch
        </td>
        <td>
            <button type="button"
                    class="btn btn-sm btn-info"
                    data-bs-toggle="modal"
                    data-bs-target="#borrowerModal_{{ $weapon_borrower->id }}">
                More
            </button>
        </td>
    </tr>

    {{-- Modal for this row --}}
    <div class="modal fade" id="borrowerModal_{{ $weapon_borrower->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Borrower Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Borrower Name:</strong> {{ $weapon_borrower->name }}</p>
                <p><strong>Receiver Officer:</strong> {{ $weapon_borrower->received_officer['name'] ?? '' }}</p>
                <p><strong>Receiver Phone:</strong> {{ $weapon_borrower->received_officer['phone'] ?? '' }}</p>
                <p><strong>Approved By:</strong> {{ $weapon_borrower->approvedBy?->name ?? '' }}</p>
                <p><strong>Status:</strong> {{ strtoupper($weapon_borrower->status) }}</p>

                {{-- Group Borrowed Weapons by Model --}}
                @php
                    $groupedWeapons = $weapon_borrower->borrowed_weapons->groupBy(function($bw) {
                        return $bw->weapon->weaponModel->name ?? 'Unknown Model';
                    });
                @endphp

                @if($groupedWeapons->isNotEmpty())
                    <p><strong>Borrowed Weapons:</strong></p>
                    <ol>
                        @foreach($groupedWeapons as $modelName => $weapons)
                            <li>
                            {{ $modelName }} (<strong>{{ count($weapons) }}</strong>)  <a href="{{ route('borrowed-weapons.model', [$weapon_borrower->id, $modelName]) }}" 
                            class="btn btn-sm btn-outline-secondary ms-2 mb-2">View</a>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p>No borrowed weapons.</p>
                @endif

                <div class="d-flex justify-content-end">
                   @if($weapon_borrower->status == 'pending') 
                    <form method="get" action="{{ route('borrowed-weapons.index') }}">
                        @csrf
                        <input type="hidden" name="weapon_borrower_id" value="{{ $weapon_borrower->id }}">
                        <button class="btn btn-sm btn-primary" type="submit">Add Weapons</button>
                    </form>
                @endif
                </div>
            </div>

            <div class="modal-footer">
                @if($weapon_borrower->status == 'pending')
                    <form method="POST" action="{{ route('weapon-borrowing.approve',$weapon_borrower->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>
                    @elseif($weapon_borrower->status == 'approved')
                    <form method="POST" action="{{ route('weapon-borrowing.return',$weapon_borrower->id) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-primary btn-sm">Return</button>
                    </form>                    
                @endif
                
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-dark btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach

            </tbody>


        </table>
    @endif

@endsection
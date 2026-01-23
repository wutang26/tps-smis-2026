@extends('layouts.main')

@section('content')
    <div class="container">
        <h2>Weapon Details</h2>
        <p><strong>Name: </strong>{{ $weapon->serial_number }} {{ $weapon->weaponModel->name ?? 'N/A' }}</p>
        <p><strong>Type: </strong>{{ $weapon->weaponModel->type->name }} ->
            {{ $weapon->weaponModel->type->category->name ?? 'N/A' }}</p>
        <p><strong>Condition:</strong> {{ ucfirst($weapon->condition) ?? 'N/A' }}</p>
        <p>
            <strong>Status:</strong>
            @if($weapon->status == 'available')
                <span class="badge bg-success">Available</span>
            @elseif($weapon->status == 'taken')
                <span class="badge bg-warning">Taken</span>
            @endif
        </p>
        @if($weapon->status == 'available')
            <a href="{{ route('weapons.handover', $weapon) }}" class="btn btn-sm btn-success">
                Handover
            </a>
        @elseif($weapon->status == 'taken')
        <form id="returnForm" action="{{ route('weapons.return', $weapon) }}" method="post">
            @csrf
            <button type="button" class="btn btn-sm btn-primary" onclick="confirmAction('returnForm', 'Weapon Handover', 'Weapon handover?','Return')">Return</button>
        </form>


        @endif
        <Center>
            <h2>Weapon Handover History</h2>
            <center>

                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Staff</th>
                            <th>Handover Amourer</th>
                            <th>Handover Date</th>
                            <th>Return Handover</th>
                            <th>Return Date</th>
                            <th>Purpose </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($weapon->handovers as $handover)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $handover->staff->forceNumber ?? '' }} {{ $handover->staff->rank ?? '' }} {{ $handover->staff->lastName ?? 'Unknown' }} </td>
                                <td>{{ $handover->handover_armorer->name }}</td>
                                <td>{{ $handover->handover_at }}</td>
                                <td>{{ $handover->return_armorer?->name }}</td>
                                <td>{{ $handover->expected_return_at ?? 'N/A' }}</td>
                                <td>{{ $handover->purpose ?? 'N/A' }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No movement history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    </div>
    @section('scripts')
        <script>
  function confirmAction(formId, itemTitle, message, action) {
    // SweetAlert confirmation
    Swal.fire({
      title: itemTitle,
      text: "Are sure you want to " + action  +' '+message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, '+action,
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        // If confirmed, submit the form
        document.getElementById(formId).submit();
      }
    });
  }
</script>
    @endsection
@endsection
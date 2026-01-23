@extends('layouts.main')

@section('title', 'Audit Logs')

@section('content')
<div class="container">
    <h3 class="mb-4">🧾 Audit Logs</h3>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="user_id" class="form-select">
                <option value="">All Users</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="action" class="form-control" placeholder="Action (e.g. delete_user)" value="{{ request('action') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Logs Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th>Metadata</th>
                    <th>Changes</th>
                    <th>IP</th>
                    <th>Browser</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                    <td><pre class="small">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre></td>
                    <td>
                        @if($log->old_values || $log->new_values)
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                            View
                        </button>
                        @endif
                    </td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->user_agent }}</td>
                    <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                </tr>

                <!-- Modal for JSON diff -->
                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-labelledby="logModalLabel{{ $log->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Change Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Old Values</h6>
                                <pre class="bg-light p-2 rounded">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                <h6>New Values</h6>
                                <pre class="bg-light p-2 rounded">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No audit logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {!! $logs->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection

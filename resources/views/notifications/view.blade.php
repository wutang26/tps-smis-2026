@extends('layouts.main')
@section('scrumb')
  <!-- Scrumb starts -->
  <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Notification View</a></li>
      </ol>
    </nav>
    </div>
  </nav>
  <!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')

  @if($category == 1)
    @php
     if (isset($notification->data))
    $notification = \App\Models\Announcement::find($notification->data['id']);
    @endphp
    <div class="mb-4 d-flex">
    @if ($notification->expires_at > \Carbon\Carbon::now())
    <img style="width: 50px; margin-top: -10px;" src="{{ asset('resources/assets/images/new_blinking.gif') }}"
    alt="new gif">
    @endif
    <h4 class="text-{{ $notification->type }}">{{ $notification->title }}</h4>
    </div>

    <p class="ms-3">{{ $notification->message }}</p>

    @if($notification->document_path)
    <a style="text-decoration: underline; color:blue; font-style:italic"
    href="{{route('download.file', ['documentPath' => $notification->id]) }}"><small>Download
    Attachment</small></a>
    @endif

    <p>
        <small>
            Announced by: 
            <i>{{ $notification->poster?->staff?->rank }} {{ $notification->poster?->name }}</i>
        </small>
    </p>
    <small>Posted At:
    {{ $notification->created_at ? $notification->created_at->format('d-m-Y H:i') : 'N/A' }}</small><br>

    @if ($notification->expires_at < \Carbon\Carbon::now())
        <small>
            Expired At: {{ $notification->expires_at?->format('d-m-Y H:i') ?? 'N/A' }}
        </small>
    @else
        <small>
            Expires At: {{ $notification->expires_at?->format('d-m-Y H:i') ?? 'N/A' }}
        </small>
    @endif
    </div>
    @can('announcement-create')
    @if($notification->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
    <div class="btn-group">
    <a style="margin-right: 10px;" href="{{ route('announcements.edit', $notification->id) }}"><button
      class="btn btn-sm btn-primary">Edit</button></a>
    <form id="deleteForm{{ $notification->id }}" action="{{ route('announcements.destroy', $notification->id) }}"
    method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button onclick="confirmDelete('deleteForm{{ $notification->id }}','notification')" type="button"
      class="btn btn-sm btn-danger">Delete</button>
    </form>
    </div>
    @include('layouts.sweet_alerts.confirm_delete')
    @endif
    @endcan
    </li>

    @can('notification-create')
        @if($notification->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
            <div class="d-flex justify-content-end mt-3">
                <div class="btn-group">
                    <a href="{{ route('announcements.edit', $notification->id) }}" class="me-2">
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </a>
                    <form id="deleteForm{{ $notification->id }}" 
                          action="{{ route('announcements.destroy', $notification->id) }}" 
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="confirmDelete('deleteForm{{ $notification->id }}','notification')" 
                                type="button" 
                                class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            @include('layouts.sweet_alerts.confirm_delete')
        @endif
    @endcan
@endif

  @if($category == 2)

    <div class="notification-card">
    <h3 class="text-{{ $notification->type ?? 'primary' }}">
    {{ $notification->title }}
    </h3><br><br>

    <p>
    <strong>Name:</strong> {{ $notification->student->force_number ?? '' }} {{ $notification->student->rank ?? '' }}
    {{ $notification->student->first_name }} {{ $notification->student->last_name }}
    </p>

    <p>
    <strong>Company:</strong>
    {{ $notification->student->company->name }} - {{ $notification->student->platoon }}
    </p>

    <p>
    <strong>Description:</strong><br>
    {{ $notification->description }}
    </p>

    <p>
    <strong>Locked at:</strong>
    {{ \Carbon\Carbon::parse($notification->arrested_at)->format('h:i A, d F Y') }}
    </p>

    @if ($notification->released_at)
    <p>
    <strong>Released at:</strong>
    {{ \Carbon\Carbon::parse($notification->released_at)->format('h:i A, d F Y') }}
    </p>
    @endif
    </div>
  @elseif ($category == 3)
    @php
    if (isset($notification->data)){
      $user = \App\Models\User::find($notification->data['requested_by']);
      $company = \App\Models\Company::find($notification->data['company_id']);
      $notification = \App\Models\AttendanceRequest::find($notification->data['id'])    ;
    }

    @endphp
    <div>
    <h3 class="text-{{ $notification->type ?? 'primary' }}">
    {{ $notification->title }}
    </h3><br><br>

    <p>
      @if (isset($notification->data))
    <strong>Requester :</strong> {{ $user->staff?->force_number ?? '' }} {{ $user->staff?->rank ?? '' }}
    {{ $user->name }}
      @else
      <strong>Requester :</strong> {{ $notification->requester->staff?->force_number ?? '' }} {{ $notification->requester->staff?->rank ?? '' }}
      {{ $notification->requester->name  }}
    @endif
    </p>
    <p>
    <strong>Requested Date:</strong> {{ $notification->date ?? '' }}
    </p>
    <p>
    <strong>Requested at:</strong>
    {{ \Carbon\Carbon::parse($notification->created_at)->format('h:s, d F, Y') ?? '' }}
    </p>
    <p>
    <strong>Company:</strong>@if (isset($notification->data)) 
    {{ $company->description ?? '' }}
    @else
      {{ $notification->company->description }}
    @endif
    </p>
    <p>
    <strong>Reason</strong> <br>{{ $notification->reason ?? '' }}
    </p>
    <p>
    <strong>Status: </strong> {{ ucfirst($notification->status) ?? '' }}
    </p>
    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal{{ $notification->id ?? ''}}">
      More
    </button>


    <div class="modal fade" id="statusModal{{  $notification->id ?? '' }}" tabindex="-1"
    aria-labelledby="statusModalLabel{{  $notification->id  ?? '' }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title text-info" id="statusModalLabel{{  $notification->id  ?? ''}}">
      Requested by 
      @if (isset($notification->data))
      {{ $user->name }}
      @else
      {{ $notification->requester->name  }}
      @endif
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div>
      <div class="mb-2">Request Reason</div>
      <small> {{ $notification->reason  }}</small><br>
      <p>Status: <strong>{{ ucfirst($notification->status ) }}</strong></p>
      </div>
      </div>
      @if($notification->status  != 'closed')
      <div class="modal-footer">
      <div class="d-flex gap-2">
      <form action="{{ route('attendance.request.update-status') }}" method="POST">
      @csrf
      <input type="text" name="attendanceRequestId" value="{{ $notification->id  }}" hidden>
      <input type="text" name="status" value="approved" hidden>
      <button @if($notification->data['status']  == 'approved' || $notification->status  == 'rejected') disabled @endif
      class="btn btn-sm btn-primary">Approve</button>
      </form>
      <form action="{{ route('attendance.request.update-status') }}" method="POST">
      @csrf
      <input type="text" name="attendanceRequestId" value="{{ $notification->id  }}" hidden>
      <input type="text" name="status" value="rejected" hidden>
      <button @if($notification->status  == 'approved' || $notification->status  == 'rejected') disabled @endif
      class="btn btn-sm btn-danger">Reject</button>
      </form>
      <form action="{{ route('attendance.request.update-status') }}" method="POST">
      @csrf
      <input type="text" name="attendanceRequestId" value="{{ $notification->id  }}" hidden>
      <input type="text" name="status" value="closed" hidden>
      <button @if($notification->data['status']  == 'rejected') disabled @endif class="btn btn-sm btn-danger">Close</button>
      </form>
      </div>
      @endif
      </div>
      </div>
    </div>
    </div>
    @endif

  @endsection
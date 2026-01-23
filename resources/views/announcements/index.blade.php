@extends('layouts.main')

@section('scrumb')
    <!-- Breadcrumb starts -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Announcements</li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Breadcrumb ends -->
@endsection

@section('content')
    @include('layouts.sweet_alerts.index')

    @can('announcement-create')
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('announcements.create') }}">
                <button class="btn btn-sm btn-success">New</button>
            </a>
        </div>
    @endcan

    <div class="card">
        <div class="card-body">
            @if ($announcements->isEmpty())
                <h4>No announcements.</h4>
            @else
                <ul class="list-group list-unstyled">
                    @foreach ($announcements as $announcement)
                        @php
                            $preview = Str::limit(strip_tags($announcement->message), 150);
                            $isLong = strlen(strip_tags($announcement->message)) > 150;
                        @endphp
                        <li class="mb-3">
                            <div class="card shadow-lg border-0 rounded-lg w-100">
                                <div class="card-body">
<div class="d-flex mb-3">
    @if ($announcement->expires_at > \Carbon\Carbon::now())
        <img src="{{ asset('resources/assets/images/new_blinking.gif') }}"
             alt="new gif" class="me-2 mt-1" style="width: 40px; height:auto;">
    @endif
    <h4 class="card-title text-{{ $announcement->type }} mb-0 lh-sm">
        {{ $announcement->title }}
    </h4>
</div>


                                    <div class="card-text ms-4">
                                        <span id="preview-{{ $announcement->id }}">{{ $preview }}</span>
                                        @if ($isLong)
                                            <span id="full-{{ $announcement->id }}" style="display:none;">
                                                {!! $announcement->message !!}
                                            </span>
                                            <button class="btn btn-link p-0" style="font-size: 0.9rem;"
                                                    onclick="toggleFullText({{ $announcement->id }})">
                                                <span id="toggle-btn-{{ $announcement->id }}">More</span>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($announcement->document_path)
                                        <div class="mt-2">
                                            <a href="{{ route('download.file', ['documentPath' => $announcement->id]) }}"
                                               style="text-decoration: underline; color: blue; font-style: italic;">
                                                <small>Download Attachment</small>
                                            </a>
                                        </div>
                                    @endif

                                    <p class="mt-2 mb-0">
                                        <small>Announced by:
                                            <i>{{ $announcement->poster->staff ? $announcement->poster->staff->rank : '' }}
                                                {{ $announcement->poster->name }}</i>
                                        </small>
                                    </p>

                                    <small>Posted At:
                                        {{ $announcement->created_at?->format('d-m-Y H:i') ?? 'N/A' }}
                                    </small><br>

                                    <small>
                                        {{ $announcement->expires_at < \Carbon\Carbon::now() ? 'Expired At:' : 'Expires At:' }}
                                        {{ $announcement->expires_at?->format('d-m-Y H:i') ?? 'N/A' }}
                                    </small>

                                    @can('announcement-create')
                                        @if ($announcement->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
                                            <div class="btn-group float-end mt-3">
                                                <a href="{{ route('announcements.edit', $announcement->id) }}">
                                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                                </a>
                                                <form id="deleteForm{{ $announcement->id }}"
                                                      action="{{ route('announcements.destroy', $announcement->id) }}"
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('deleteForm{{ $announcement->id }}', 'Announcement')">
                                                        Delete
                                                    </button>
                                                </form>
                                                @include('layouts.sweet_alerts.confirm_delete')
                                            </div>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script>
        function toggleFullText(id) {
            const preview = document.getElementById('preview-' + id);
            const full = document.getElementById('full-' + id);
            const btn = document.getElementById('toggle-btn-' + id);

            const isHidden = full.style.display === 'none';
            full.style.display = isHidden ? 'inline' : 'none';
            preview.style.display = isHidden ? 'none' : 'inline';
            btn.textContent = isHidden ? 'Less' : 'More';
        }
    </script>
@endsection

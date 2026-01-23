@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Posts</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')
<div class="row gx-4">
  <div class="col-sm-8">
    <div class="card mb-3">
      <div class="card-header"></div>
      <div class="pull-right">
<button id="addPlatoonBtn" 
        class="btn btn-success btn-sm mb-3" 
        style="float:right !important; margin-right:1%"
        data-bs-toggle="modal" 
        data-bs-target="#platoonModal">
    <i class="fa fa-plus"></i> New Post
</button>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Session Name</th>
                  <th>Status</th>
                  <th>Publisher</th>
                  <th width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($posts as $post)
                  <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ $post->session->session_programme_name }}</td>
                    <td>
                      @php
                          $statusClasses = [
                              'pending' => 'badge bg-warning',
                              'published' => 'badge bg-success',
                          ];
                          $statusClass = $statusClasses[strtolower($post->status)] ?? 'badge bg-secondary';
                      @endphp

                      <span class="{{ $statusClass }}">
                          {{ ucfirst($post->status) }}
                      </span>
                    </td>
                    <td>{{ $post->publisher?->name }}</td>
                    <td class="d-flex gap-2">
                    <form id="publishForm{{ $post->id }}" action="{{ route('post.publish', $post->id) }}" method="post">
                        @csrf
                        @php
                            // Get the user's post (from session or database)
                            $uploadedPost = session('uploaded_post') ?? $post ?? null;
                        @endphp

                      <div class="text-end">
                        @can('post-create')
                            {{-- Case 1: No uploaded post yet --}}
                            @if ($uploadedPost->student_posts->isEmpty())
                                <a href="{{ route('students-post.create') }}" class="btn btn-success btn-sm">Upload</a>

                            {{-- Case 2: Has uploaded post but still pending --}}
                            @elseif ($uploadedPost->status === 'pending')
                                <a href="{{ route('students-post.edit_post') }}" class="btn btn-info btn-sm">Update</a>

                                <button class="btn btn-sm btn-primary" type="button"
                                    onclick="confirmAction('publishForm{{ $uploadedPost->id }}', 'Publish Post','Post','Publish')">
                                    Publish
                                </button>

                            {{-- Case 3: Post already published --}}
                            @elseif ($uploadedPost->status === 'published')
                                <button class="btn btn-sm btn-warning" type="button"
                                    onclick="confirmAction('publishForm{{ $uploadedPost->id }}', 'Unpublish Post','Post','Unpublish')">
                                    Unpublish
                                </button>
                            @endif
                        @endcan
                      </div>

                      </form>
                    @if($post->status == 'pending')
                    <form id="deleteForm{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmAction('deleteForm{{ $post->id }}', 'Delete Post','Post','Delete')">Delete</button>
                    </form>
                    @endif
                    </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  
<!-- Modal -->
<div class="modal fade" id="platoonModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="platoonForm" method="POST" action="{{ route('posts.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Create New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Session Programme -->
                    <div class="mb-3">
                        <label for="sessionProgramme" class="form-label">Session</label>
                        <select name="session_programme_id" id="sessionProgramme" class="form-select" required>
                            <option value="" disabled selected>Select session programme</option>
                            @foreach ($session_programmes as $session_programme)
                                <option value="{{ $session_programme->id }}">{{ $session_programme->session_programme_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="">Save Post</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->

@endsection

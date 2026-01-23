@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Announcements</a>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
    @endsession  
   
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('3a9b85e8ad0fb87a0a56', {
      cluster: 'mt1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        alert(JSON.stringify(data))
      app.messages.push(JSON.stringify(data));
    });


  </script>
    <div style="display: flex; justify-content: end;">
        <a href="{{ route('announcements.create') }}"><button class="btn btn-sm btn-success">New</button></a>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="list-group">
                @foreach ($announcements as $announcement)
                            <li class="list-group-item d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <h4 class="text-{{ $announcement->type }}">{{ $announcement->title }}</h4>
                                    <p> &nbsp &nbsp &nbsp{{ $announcement->message }}</p>
                                    <p><small>Posted by: <i>{{ $announcement->poster->name }}</i></small></p>
                                    <small>Expires At:
                                        {{ $announcement->expires_at ? $announcement->expires_at->format('d-m-Y H:i') : 'N/A' }}</small>

                            </div>
                                <div class="btn-group">
                                    <a style="margin-right: 10px;" href="{{ route('announcements.edit', $announcement->id) }}"><button
                                            class="btn btn-sm btn-primary">Edit</button></a>
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </li>
                @endforeach
            </ul>
        </div>
        <script>



    </script>
@endsection
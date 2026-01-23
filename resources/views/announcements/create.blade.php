@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="#">Announcements</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="#">Create</a>
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
    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row gx-4">
            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Title </label>
                            <input type="text" class="form-control" value="{{old('title')}}" id="title" name="title"
                                placeholder="Announcement title" required>
                        </div>
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label for="expires_at">Expires At </label>
                            <input class="form-control" value="{{old('expires_at')}}" type="datetime-local"
                              min="{{ \Carbon\Carbon::today() }}"  name="expires_at" id="expires_at">
                        </div>
                        @error('expires_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label for="type">Type</label>
                        <select class="form-control" name="type" id="type" required>
                            <option selected value="" disabled>select type</option>
                            <option value="info">Info</option>
                            <option value="success">Success</option>
                            <option value="danger">Danger</option>
                            <option value="warning">Warning</option>
                        </select>
                    </div>
                    @error('type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        </div>

        <div class="row gx-4">
            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label for="type">Audience</label>
                            <select class="form-control" name="audience" id="type" required>
                                <option selected value="" disabled>select audience</option>
                                @foreach (Auth::user()->roles as $role)
                                    @if (!($role->name == "Teacher" || $role->name == "Sir Major"))
                                        <option value="all">All</option>
                                        <option value="staff">Staff</option>
                                        @break
                                    @endif
                                @endforeach
                                <option value="company">Company</option>
                            </select>
                        </div>
                        @error('audience')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label for="type">Attachment(pdf) - Optional</label><br>
                            <input class="form-control" type="file" accept="application/pdf" name="document" id="">
                        </div>
                        @error('document')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="m-0">
                    <label class="form-label" for="abc">Message </label>
                    <textarea value="{{old('message')}}" class="form-control" name="message" id="message" required
                        placeholder="type here......"></textarea>
                </div>
                @error('message')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
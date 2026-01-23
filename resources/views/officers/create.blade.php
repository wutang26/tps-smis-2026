@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Add New Officer</h3>
    <form method="POST" action="{{ route('officers.store') }}">
        @csrf
        @include('officers.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection

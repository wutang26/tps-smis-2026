@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Edit Officer</h3>
    <form method="POST" action="{{ route('officers.update', $officer) }}">
        @csrf
        @method('PUT')
        @include('officers.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection

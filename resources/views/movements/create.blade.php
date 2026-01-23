@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>{{ isset($movement) ? 'Edit Movement' : 'Record New Movement' }}</h3>
    <form method="POST" action="{{ isset($movement) ? route('movements.update', $movement) : route('movements.store') }}">
        @csrf
        @if(isset($movement)) @method('PUT') @endif
        @include('movements.form')
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection

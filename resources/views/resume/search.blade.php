@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-12 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-4 text-center">Search Staff Resume</h2>
    @if(session('error'))
        <div class="text-red-500 mb-4">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('resume.display') }}">
        @csrf
        <label class="block mb-2 font-medium">Enter Force Number</label>
        <input type="text" name="force_number" class="w-full border border-gray-300 p-2 rounded mb-4" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">View Resume</button>
    </form>
</div>
@endsection

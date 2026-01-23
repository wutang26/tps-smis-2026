<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = Officer::latest()->paginate(10);
        return view('officers.index', compact('officers'));
    }

    public function create()
    {
        return view('officers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|unique:officers',
            'service_number' => 'required|unique:officers',
            'full_name' => 'required',
            'rank' => 'required',
        ]);

        Officer::create($request->all());

        return redirect()->route('officers.index')->with('success', 'Officer added successfully.');
    }

    public function show(Officer $officer)
    {
        return view('officers.show', compact('officer'));
    }

    public function edit(Officer $officer)
    {
        return view('officers.edit', compact('officer'));
    }

    public function update(Request $request, Officer $officer)
    {
        $request->validate([
            'full_name' => 'required',
            'rank' => 'required',
            'status' => 'required',
        ]);

        $officer->update($request->all());

        return redirect()->route('officers.index')->with('success', 'Officer updated successfully.');
    }

    public function destroy(Officer $officer)
    {
        $officer->delete();
        return redirect()->route('officers.index')->with('success', 'Officer deleted successfully.');
    }
}

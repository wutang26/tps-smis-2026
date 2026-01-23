<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    // Display a listing of certificates
    public function index()
    {
        $certificates = Certificate::all();
        return view('settings.certificates.index', compact('certificates'));
    }

    // Show the form for creating a new certificate
    public function create()
    {
        return view('settings.certificates.create');
    }

    // Store a newly created certificate in storage
    public function store(Request $request)
    {
        $request->validate([
            'certificate_name' => 'required|string|max:255|unique:certificates',
            'description' => 'nullable|string',
            'student_photo' => 'required|boolean',
            'status' => 'required|integer',
            'background_image' => 'required|image|max:2048' // Updated field
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('certificates', 'public');
        }

        // Save certificate details
        Certificate::create([
            'certificate_name' => $request->input('certificate_name'),
            'description' => $request->input('description'),
            'student_photo' => $request->input('student_photo'),
            'status' => $request->input('status'),
            'background_image' => $imagePath // Updated field
        ]);

        return redirect()->route('certificates.index')->with('success', 'Certificate template created successfully!');
    }

    // Display a specific certificate
    public function show(Certificate $certificate)
    {
        return view('settings.certificates.show', compact('certificate'));
    }

    // Show the form for editing a certificate
    public function edit(Certificate $certificate)
    {
        $certificates = Certificate::all();
        return view('settings.certificates.edit', compact('certificate','certificates' ));
    }
    
    // Update the specified certificate in storage
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'certificate_name' => 'required|string|max:255|unique:certificates,certificate_name,' . $certificate->id,
            'description' => 'nullable|string',
            'student_photo' => 'required|boolean',
            'status' => 'required|integer',
            'background_image' => 'nullable|image|max:2048' // Image is optional during edit
        ]);

        // Handle background image update
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('certificates', 'public');
            $certificate->background_image = $imagePath;
        }

        // Update other details
        $certificate->update([
            'certificate_name' => $request->input('certificate_name'),
            'description' => $request->input('description'),
            'student_photo' => $request->input('student_photo'),
            'status' => $request->input('status')
        ]);

        return redirect()->route('certificates.index')->with('success', 'Certificate updated successfully!');
    }


    // Remove a certificate from storage
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('certificates.index')->with('success', 'Certificate deleted successfully!');
    }

}

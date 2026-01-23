<?php

namespace App\Http\Controllers;

use App\Models\TrainingAndWorkshop;
use Illuminate\Http\Request;

class TrainingAndWorkshopController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'training_name' => 'required|string|max:255',
            'training_description' => 'nullable|string',
            'institution' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->only([
            'training_name',
            'training_description',
            'institution',
            'start_date',
            'end_date',
        ]);

        if ($request->hasFile('certificate')) {
            $data['certificate'] = $request->file('certificate')->store('certificates');
        }

        $request->user()->trainingsAndWorkshops()->create($data);

        return redirect()->back()->with('success', 'Training/Workshop added successfully!');
    }

    public function destroy($id)
    {
        $training = TrainingAndWorkshop::findOrFail($id);
        $training->delete();

        return redirect()->back()->with('success', 'Training/Workshop removed successfully!');
    }
}

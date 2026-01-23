<?php

namespace App\Http\Controllers;

use App\Models\WeaponMovement;
use App\Models\Weapon;
use App\Models\Officer;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function index()
    {
        $movements = WeaponMovement::latest()->paginate(10);
        return view('movements.index', compact('movements'));
    }

    public function create()
    {
        $weapons = Weapon::all();
        $officers = Officer::where('status', 'Active')->get();
        return view('movements.create', compact('weapons', 'officers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movement_id' => 'required|unique:weapon_movements',
            'weapon_id' => 'required|exists:weapons,id',
            'movement_type' => 'required',
            'purpose' => 'required',
            'issue_date_time' => 'required|date',
            'issued_by_officer_id' => 'required|exists:officers,id',
            'issued_to_officer_id' => 'required|exists:officers,id',
        ]);

        WeaponMovement::create($request->all());

        return redirect()->route('movements.index')->with('success', 'Movement recorded.');
    }

    public function show(WeaponMovement $movement)
    {
        return view('movements.show', compact('movement'));
    }

    public function edit(WeaponMovement $movement)
    {
        $weapons = Weapon::all();
        $officers = Officer::where('status', 'Active')->get();
        return view('movements.edit', compact('movement', 'weapons', 'officers'));
    }

    public function update(Request $request, WeaponMovement $movement)
    {
        $request->validate([
            'movement_type' => 'required',
            'purpose' => 'required',
            'issued_by_officer_id' => 'required|exists:officers,id',
            'issued_to_officer_id' => 'required|exists:officers,id',
        ]);

        $movement->update($request->all());

        return redirect()->route('movements.index')->with('success', 'Movement updated.');
    }

    public function destroy(WeaponMovement $movement)
    {
        $movement->delete();
        return redirect()->route('movements.index')->with('success', 'Movement deleted.');
    }
}

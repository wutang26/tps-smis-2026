<?php

namespace App\Http\Controllers;

use App\Models\WeaponBorrower;
use Illuminate\Http\Request;

class WeaponBorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load borrowed weapons and their weapon and weaponModel
        $weapon_borrowers = WeaponBorrower::with('borrowed_weapons.weapon.weaponModel')->get();

        return view('weapons.borrowing.index', compact('weapon_borrowers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'received_officer_name' => 'required|string|max:255',
            'received_officer_phone' => 'required|string|max:20',
            'start_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:start_date',
        ]);

        WeaponBorrower::create([
            'name' => $request->name,
            'received_officer' => [
                'name' => $request->received_officer_name,
                'phone' => $request->received_officer_phone
            ],
            'start_date' => $request->start_date,
            'expected_return_date' => $request->expected_return_date,
            'armorer_id' => $request->user()->id
        ]);

        return redirect()->back()->with('success', 'Weapon borrowing made successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(WeaponBorrower $borrower, $model)
    {
        // Load borrowed weapons with weapon and model
        $borrower->load('borrowed_weapons.weapon.weaponModel');

        // Filter borrowed weapons by model name
        $weaponsOfModel = $borrower->borrowed_weapons->filter(function ($bw) use ($model) {
            return $bw->weapon->weaponModel->name === $model;
        });

        return view('weapons.borrowing.show', compact('borrower', 'model', 'weaponsOfModel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeaponBorrower $weaponBorrower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeaponBorrower $weaponBorrower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeaponBorrower $weaponBorrower)
    {
        $weaponBorrower->delete();
        return redirect()->back()->with('success', 'Borrower record deleted successfully.');
    }

    public function approve($id)
    {
        $borrower = WeaponBorrower::findOrFail($id);

        // update status
        $borrower->status = 'approved';
        //$borrower->approved_at = now();
        $borrower->approved_by = auth()->id(); // the logged-in officer
        $borrower->save();

        return redirect()->back()->with('success', 'Weapon borrowing request approved successfully.');
    }


    public function reject($id)
    {
        $borrower = WeaponBorrower::findOrFail($id);

        $borrower->status = 'rejected';
        //$borrower->approved_at = now();
        $borrower->approved_by = auth()->id();
        $borrower->save();

        return redirect()->back()->with('success', 'Weapon borrowing request rejected.');
    }


        public function return($id)
    {
        $borrower = WeaponBorrower::findOrFail($id);

        $borrower->status = 'returned';
        $borrower->returned_at = now();
        $borrower->approved_by = auth()->id();

        $borrower->save();

        return redirect()->back()->with('success', 'Weapon borrowing request rejected.');
    }



}

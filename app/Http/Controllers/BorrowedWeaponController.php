<?php

namespace App\Http\Controllers;

use App\Models\BorrowedWeapon;
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponType;
use Illuminate\Http\Request;

class BorrowedWeaponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query with eager loading
        $query = Weapon::where('status', 'available')->with(['weaponModel.type', 'weaponModel.category']);

        //Apply filters
        if ($request->filled('category')) {
            $category = $request->category;

            $query->whereHas('weaponModel.type.category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('weaponModel', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%") // model name
                            ->orWhereHas('type', function ($t) use ($search) {
                                $t->where('name', 'like', "%{$search}%"); // type name
                            })
                            ->orWhereHas('type.category', function ($c) use ($search) {
                                $c->where('name', 'like', "%{$search}%"); // category name
                            });
                    });
            });
        }
        $weapon_borrower_id = $request->weapon_borrower_id;
        $weapons = $query->get();
        return view('weapons.borrowing.weapons', compact(
            'weapons',
            'weapon_borrower_id'
        ));
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
        $request->validate([
        'weapon_borrower_id' => 'required|exists:weapon_borrowers,id',
        'weapon_ids'         => 'required|array|min:1',
        'weapon_ids.*'       => 'exists:weapons,id',
    ]);

    $borrowerId = $request->weapon_borrower_id;
    $weaponIds  = $request->weapon_ids;

        // Loop through selected weapons and create a record linking weapon to borrower
    foreach ($weaponIds as $weaponId) {
        $weapon = Weapon::find($weaponId);
        $weapon->status = 'borrowed';
        $weapon->save();
        // Assuming you have a pivot table or handover table
        BorrowedWeapon::create([
            'weapon_id'       => $weapon->id,
            'weapon_borrower_id' => $borrowerId,
        ]);
        
    }

    return redirect()->back()->with('success', 'Weapon(s) have been assigned to the borrower.');

    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowedWeapon $borrowedWeapon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowedWeapon $borrowedWeapon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowedWeapon $borrowedWeapon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowedWeapon $borrowedWeapon)
    {
        //
    }
}

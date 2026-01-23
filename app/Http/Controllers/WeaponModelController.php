<?php

namespace App\Http\Controllers;

use App\Models\WeaponModel;
use App\Models\WeaponCategory;
use App\Models\WeaponType;
use Illuminate\Http\Request;

class WeaponModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query with eager loading weaponModel and its category
        $query = WeaponModel::with(['category']);

        if ($request->filled('category')) {
            $query->whereHas('type.category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter by search term (serial number or weapon model name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        // Paginated result
        $models = $query->paginate(10);
        $weaponTypes = WeaponType::all();
        $categories = WeaponCategory::with('types.models')->get();

        return view('weapons.models.index', compact(
            'models',
            'weaponTypes',
            'categories',
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
        $validated = $request->validate([
            'name' => 'required',
            'description' =>'required|string',
            'weapon_type_id' => 'required|exists:weapon_types,id',
        ]);

        WeaponModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'weapon_type_id' => $validated['weapon_type_id'],
        ]);

        return redirect()->route('weapon-models.index')->with('success', 'Weapon added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WeaponModel $weaponModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeaponModel $weaponModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeaponModel $weaponModel)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' =>'required|string',
            'weapon_type_id' => 'required|exists:weapon_types,id',
        ]);
        $weaponModel->name = $validated['name'];
        $weaponModel->description = $validated['description'];
        $weaponModel->weapon_type_id = $validated['weapon_type_id'];
        $weaponModel->save();
        return redirect()->back()->with('success', 'Weapon Model updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeaponModel $weaponModel)
    {
        //
    }
}

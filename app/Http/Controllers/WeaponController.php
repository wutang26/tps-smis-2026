<?php
namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\WeaponModel;
use App\Models\WeaponType;
use Illuminate\Http\Request;
use App\Models\WeaponCategory;
use App\Models\WeaponOwnershipType;
use App\Models\Company;
use App\Models\Staff;
use App\Models\WeaponHandover;
use App\Models\Handover;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Imports\WeaponImport;
use Exception;

class WeaponController extends Controller
{
    public function index(Request $request)
    {
        //return $request->all();
        // Base query with eager loading weaponModel and its category
        $query = Weapon::where('weaponModel_id',$request->model_id)->with(['weaponModel.category']);
        //areturn $request->all();
        // Filter by category name
        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

        // Filter by search term (serial number or weapon model name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('weaponModel', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Paginated result
        $weapons = $query->paginate(50);

        // Total count after filters (use paginate's total to avoid extra query)
        $totalWeapons = $weapons->total();

        // Load related data for the view
        $categories = WeaponCategory::with('types.models')->get();
        $ownershipTypes = WeaponOwnershipType::all();
        $companies = Company::where('campus_id', 1)->get();
        $model = WeaponModel::find($request->model_id);
        return view('weapons.index', compact(
            'weapons',
            'totalWeapons',
            'categories',
            'ownershipTypes',
            'companies',
            'model'
        ));
    }



    public function create()
    {
        $categories = WeaponCategory::with('types.models')->get();

        return view('weapons.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|unique:weapons,serial_number',
            'weapon_ownership_type_id' => 'required|exists:weapon_ownership_types,id',

            // company_id always nullable and optional
            'company_id' => 'nullable|exists:companies,id',

            // owner details required only if ownership type is NOT 1
            'owner_name' => 'required_unless:weapon_ownership_type_id,1|nullable|string|max:255',
            'owner_phone' => ['required_unless:weapon_ownership_type_id,1', 'nullable', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
            'owner_nin' => 'required_unless:weapon_ownership_type_id,1|nullable|string|max:50',
        ]);



        $ownerData = null;
        $companyId = null;

        if ($validated['weapon_ownership_type_id'] == 1) {
            $companyId = $validated['company_id'];
        } else {
            $ownerData = [
                'name' => $validated['owner_name'],
                'phone' => $validated['owner_phone'],
                'nin' => $validated['owner_nin'],
            ];
        }

        Weapon::create([
            'serial_number' => $validated['serial_number'],
            'weaponModel_id' => $request->weaponModel_id,
            'weaponOwnershipType_id' => $validated['weapon_ownership_type_id'],
            'company_id' => $companyId,
            'owner' => $ownerData,
        ]);

                return redirect()->route('weapons.index',[
    'model_id' => $request->weaponModel_id])->with('success', 'Weapon added successfully.');
    }


    public function show(Weapon $weapon)
    {
        $weapon->load([
            'handovers' => function ($q) {
                $q->orderBy('handover_at', 'desc');
            },
            'weaponModel.type',
            'weaponModel.category'
        ]);
        return view('weapons.show', compact('weapon'));
    }

    public function edit(Weapon $weapon)
    {
        // Eager-load the weapon's model, type, and category
        $weapon->load('model.type', 'model.category');

        // Fetch all models with their type & category (for dropdown)
        $models = WeaponModel::with(['type', 'category'])->get();

        return view('weapons.edit', compact('weapon', 'models'));
    }

    public function update(Request $request, Weapon $weapon)
    {
        $validated = $request->validate([
            'serial_number' => 'required|unique:weapons,serial_number,' . $weapon->id,
            'weaponModel_id' => 'required|exists:weapon_models,id',
            'weapon_ownership_type_id' => 'required|exists:weapon_ownership_types,id',

            // company_id always nullable and optional
            'company_id' => 'nullable|exists:companies,id',

            // owner details required only if ownership type is NOT 1
            'owner_name' => 'required_unless:weapon_ownership_type_id,1|nullable|string|max:255',
            'owner_phone' => ['required_unless:weapon_ownership_type_id,1', 'nullable', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
            'owner_nin' => 'required_unless:weapon_ownership_type_id,1|nullable|string|max:50',
        ]);



        $ownerData = null;
        $companyId = null;

        if ($validated['weapon_ownership_type_id'] == 1) {
            $companyId = $validated['company_id'];
        } else {
            $ownerData = [
                'name' => $validated['owner_name'],
                'phone' => $validated['owner_phone'],
                'nin' => $validated['owner_nin'],
            ];
        }

        $weapon->update([
            'serial_number' => $validated['serial_number'],
            'weaponModel_id' => $validated['weaponModel_id'],
            'weaponOwnershipType_id' => $validated['weapon_ownership_type_id'],
            'company_id' => $companyId,
            'owner' => $ownerData,
        ]);

        return redirect()->route('weapons.index',[
    'model_id' => $weapon->weaponModel_id])
    ->with('success', 'Weapon updated successfully!');

    }

    public function destroy(Weapon $weapon)
    {
        $weapon->delete();
        return redirect()->route('weapons.index')->with('success', 'Weapon deleted successfully.');
    }

public function storeHandover(Request $request, Weapon $weapon)
{
    // Check if weapon is already handed over
    if ($weapon->status === 'taken') {
        return redirect()->route('weapons.show', $weapon)
            ->with('error', 'This weapon is already handed over.');
    }

    $request->validate([
        'staff_id' => 'required|exists:staff,id',
        //'handover_armorer_id' => 'required|exists:staff,id',
        'handover_at' => 'required|date',
        'expected_return_at' => 'required|date|after:handover_at',
        'purpose' => 'required|string',
        'remarks' => 'nullable|string',
    ]);
    
    WeaponHandover::create([
        'weapon_id' => $weapon->id,
        'handover_armorer_id' => $request->user()->id,
        'staff_id' => $request->staff_id,
        'handover_at' => now(),
        'expected_return_at' => $request->expected_return_at,
        'purpose' => $request->purpose,
        'remarks' => $request->remarks ?? null,
    ]);

    $weapon->status = 'taken';
    $weapon->save();

    return redirect()->route('weapons.show', $weapon)
        ->with('success', 'Weapon handover recorded successfully.');
}

    // Show handover form

    public function handover(Weapon $weapon)
    {
        return view('weapons.handover', compact('weapon'));
    }



    public function return(Request $request, Weapon $weapon)
    {
        $weaponHandover = WeaponHandover::where('weapon_id', $weapon->id)->whereNull('returned_at')->first();
        if (!$weaponHandover) {
            return redirect()->back()->with('info', 'Weapon is not handedover.');
        }

        $weaponHandover->return_armorer_id = $request->user()->id;
        $weaponHandover->returned_at = now();
        $weapon->status = 'available';
        $weapon->save();
        $weaponHandover->save();
        return redirect()->back()->with('success', 'Weapon return successfully.');
    }
    // Mark weapon as returned
    public function returnWeapon(Handover $handover)
    {
        $handover->update([
            'status' => 'returned',
            'return_date' => now(), // auto-fill when returned
        ]);

        // Mark weapon back as available
        $handover->weapon->update(['status' => 'available']);

        return redirect()->route('weapons.show', $handover->weapon)
            ->with('success', 'Weapon marked as returned.');
    }

    public function get_upload()
    {
        return view('weapons.uploads');
    }


    public function bulkimport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        try {
            Excel::import(new WeaponImport, filePath: $request->file('import_file'));
        } catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return redirect()->route('weapons.uploads')->with('success', 'Weapons Uploaded  successfully.');
    }

        public function downloadSample()
    {
        $path = storage_path('app/public/sample/weapons..xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }

    public function summary(){
        return view('weapons.summary');
    }
}

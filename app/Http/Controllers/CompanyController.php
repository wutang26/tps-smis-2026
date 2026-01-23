<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Platoon;
use App\Models\Campus;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:campus-create')->only(['create', 'store']);
        $this->middleware('permission:campus-list')->only(['index', 'show']);
        $this->middleware('permission:campus-edit')->only(['edit', 'update']);
        $this->middleware('permission:campus-delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::get();
        return view('settings.companies.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campuses = Campus::get();
        return view('settings.companies.create', compact('campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'name' => 'required|unique:companies,name',
            'campus_id' => 'required',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        Company::create($request->all());
    
        return redirect()->route('companies.index')
                        ->with('success','Company added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('settings.companies.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $campuses = Campus::get();
        return view('settings.companies.edit',compact('company','campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, AuditLoggerService $auditLogger)
    {
        request()->validate([
            'name' => 'required|unique:companies,name,'.$company->id,
            'campus_id' => 'required',
            'description' => 'required',
        ]);
    $companySnapshot = clone $company;
       $company->update($request->all());
           
        $auditLogger->logAction([
            'action' => 'update_company',
            'target_type' => 'Company',
            'target_id' => $companySnapshot->id,
            'metadata' => [
                'title' => $companySnapshot->name ?? null,
            ],
            'old_values' => [
                'company' => $companySnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
       return redirect()->route('companies.index')
                       ->with('success','Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Request $request, AuditLoggerService $auditLogger)
    {
        $companySnapshot = clone $company;
        $company->delete();
        $auditLogger->logAction([
            'action' => 'delete_company',
            'target_type' => 'Company',
            'target_id' => $companySnapshot->id,
            'metadata' => [
                'title' => $companySnapshot->name ?? null,
            ],
            'old_values' => [
                'company' => $companySnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('companies.index')
                        ->with('success','Campus deleted successfully');
    }

    public function create_platoon(Request $request, $companyId){
        
        $company = Company::find($companyId);
        
        if(empty($company)){
            return redirect()->back()->with('error', 'Company is not found');
        }
        $validator = Validator::make($request->all(),[
            'name'=> ['required',
                        'string',
                Rule::unique('platoons')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->company_id);
        }),
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
       Platoon::create([
            'company_id'=> $company->id,
            'name' => $request->name            
        ]);

                return redirect()->route('companies.index')
                        ->with('success','Platoon created successfully');
    }

        public function destroy_platoon(Request $request, $platoonId)
    {
        $platoon = Platoon::find($platoonId);
        $platoon->delete();
    
        return redirect()->route('companies.index')
                        ->with('success','Platoon deleted successfully');
    }
}

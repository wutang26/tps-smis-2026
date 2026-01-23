<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLoggerService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {

        $roles = Role::orderBy('id','DESC')->paginate(10);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('description')->get();
        return view('roles.create',compact('permissions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);


        $role = Role::create(['name' => $request->input('name')]);

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
        
        $role->syncPermissions($permissionsID);
    
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        
        // Group permissions by category 
        $permissions = [ 
            'Role Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'role'); }), 
            'Product Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'product'); }), 
            'Student Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'student'); }), 
            'Department Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'department'); }), 
            'Programme Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'programme'); }), 
            'Coursework Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'coursework'); }), 
            'Semester Exam Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'semester-exam'); }), 
            'Certificate Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'print-certificate'); }), 
            'Hospital Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'hospital'); }), 
            'Settings Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'setting'); }), 
            'Programme Session Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'programme-session'); }), 
            'Report Management' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'report'); }), 
            'Student Enrollment' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'enroll-students'); }), 
            'Result Generation' => $rolePermissions->filter(function ($permission) { return str_contains($permission->name, 'generate-results'); }),
        ];
    
        return view('roles.show',compact('role','permissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id): View
    // {
    //     $role = Role::find($id);
    //     $permission = Permission::get();
    //     $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
    //         ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
    //         ->all();
    
    //     return view('roles.edit',compact('role','permission','rolePermissions'));
    // }

    public function edit($id):View
{
    $role = Role::find($id);
    $permissions = Permission::orderBy('description')->get();
    $rolePermissions = $role->permissions->pluck('id')->toArray(); // Get user's current permissions
    return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
}

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
    
        $role->syncPermissions($permissionsID);
    
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request, AuditLoggerService $auditLogger): RedirectResponse
    {
        $role = Role::find($id);
        $roleSnapshot = $role;
        $roleSnapshot->delete();
        $auditLogger->logAction([
            'action' => 'delete_role',
            'target_type' => 'Role',
            'target_id' => $roleSnapshot->id,
            'metadata' => [
                'title' => $roleSnapshot->name ?? null,
            ],
            'old_values' => [
                'role' => $roleSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}

<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class UserController extends Controller
{ 
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','view']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update','updateProfile']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
         $this->middleware('permission:user-profile', ['only' => ['profile']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $users = User::latest()->paginate(20);
        $roles = Role::all();
  
        return view('users.index',compact('users','roles'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function search(Request $request)
{
    $users = User::query();

    // Filter by role if provided
    if ($request->role) {
        $users->whereHas('roles', function ($query) use ($request) {
            $query->where('id', $request->role);
        });
    }

    // Filter by name/email if provided
    if ($request->name) {
        $users->where(function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('email', 'like', '%' . $request->name . '%');
        });
    }

    // Apply ordering and pagination
    $users = $users->orderBy('name')
                   ->paginate(20)
                   ->appends($request->all()); // 👈 keep filters in pagination links

    $roles = Role::all();

    return view('users.index', compact('users', 'roles'))
        ->with('i', ($request->input('page', 1) - 1) * 20);
}

    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.create',compact('roles'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */   

    public function changePassword($id): View
    {
        $user = User::findOrFail($id);
        return view('users.changePassword',compact('user'));
    }

    // Handle the password update
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::findOrFail($id);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect('/')->with('status', 'Password changed successfully.');
    }


    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        
        return view('users.edit',compact('user','roles','userRole'));
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request, AuditLoggerService $auditLogger): RedirectResponse
    {
        $user = User::find($id);
        $userSnapshot = $user;
        $roles = $user->getRoleNames();
        $user->delete();
        $auditLogger->logAction([
            'action' => 'delete_User',
            'target_type' => 'User',
            'target_id' => $userSnapshot->id,
            'metadata' => [
                'title' => $userSnapshot->name,
                'roles' => $roles
            ],
            'old_values' => [
                'user' => $userSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}

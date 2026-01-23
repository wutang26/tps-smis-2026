<?php

namespace App\Http\Controllers;

use App\Models\Vitengo;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
class VitengoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vitengo-create')->only(['create', 'store']);
        $this->middleware('permission:vitengo-view')->only(['index', 'show']);
        $this->middleware('permission:vitengo-edit')->only(['edit', 'update']);
        $this->middleware('permission:vitengo-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        $vitengo = Vitengo::orderBy('id', 'Asc')->paginate(13);

       
        
        return view('vitengo.index', compact('vitengo'))
            ->with('i', ($request->input('page', 1) - 1) * 13);
    }

    public function create()
    {
        return view('vitengo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Vitengo::create($request->all());

        return redirect()->route('vitengo.index')
            ->with('success', 'Kitengo '.$request->name.' created successfully.');
    }

    public function show(Vitengo $vitengo)
    {

        return view('vitengo.show', compact('vitengo'));
    }

    public function edit(Vitengo $vitengo)
    {
        return view('vitengo.edit', compact('vitengo'));
    }

    public function update(Request $request, Vitengo $vitengo)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean',
        ]);

        try {
            // Update the Vitengo record
            $vitengo->update([
                'name' => $request->name,
            ]);

            // Redirect with success message
            return redirect()->route('vitengo.index')
                ->with('success', 'Kitengo '.$vitengo->name.' updated successfully.');

        } catch (\Exception $e) {
            // Handle unexpected errors
            return redirect()->back()
                ->with('error', 'Failed to update Kitengo. Please try again later.');
        }
    }

    public function destroy(Vitengo $Vitengo, Request $request, AuditLoggerService $auditLogger)
    {
        $vitengoSnapshot = $Vitengo;
        $Vitengo->delete();
        $auditLogger->logAction([
            'action' => 'delete_kitengo',
            'target_type' => 'Vitengo',
            'target_id' => $vitengoSnapshot->id,
            'metadata' => [
                'title' => $vitengoSnapshot->name ?? null,
            ],
            'old_values' => [
                'kitengo' => $vitengoSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('vitengo.index')
            ->with('success', 'Kitengo  '.$Vitengo->name.'  deleted successfully.');
    }

    public function activate(Request $request)
    {
        $kitengo = Vitengo::find($request->kitengo_id);
        if (! $kitengo) {
        }
        $kitengo->is_active = true;
        $kitengo->save();

        return redirect()->route('vitengo.index')
            ->with('success', 'Kitengo '.$kitengo->name.'deactivated successfully.');
    }

    public function deactivate(Request $request)
    {
        $kitengo = Vitengo::find($request->kitengo_id);
        if (! $kitengo) {
        }
        $kitengo->is_active = false;
        $kitengo->save();

        return redirect()->route('vitengo.index')
            ->with('success', 'Kitengo '.$kitengo->name.' activated successfully.');
    }
}

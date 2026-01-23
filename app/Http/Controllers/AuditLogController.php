<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogController extends Controller
{
        public function index(Request $request)
        {
            $query = AuditLog::with('user');

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('action')) {
                $query->where('action', 'like', '%' . $request->action . '%');
            }

            if ($request->filled('from')) {
                $query->whereDate('created_at', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $query->whereDate('created_at', '<=', $request->to);
            }

            $logs = $query->latest()->paginate(20);
            $users = User::orderBy('name')->get();

            return view('audit_logs.index', compact('logs', 'users'));
        }

}

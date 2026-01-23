<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Helpers\AuditHelper;
use Illuminate\Contracts\Auth\Authenticatable;

class AuditLoggerService
{
    public function logAction(array $params): void
    {
        // Required
        $action       = $params['action'];         // e.g., delete_coursework
        $targetType   = $params['target_type'] ?? null;  // e.g., CourseWork
        $targetId     = $params['target_id'] ?? null;

        // Optional
        $metadata     = $params['metadata'] ?? null;
        $oldValues    = $params['old_values'] ?? null;
        $newValues    = $params['new_values'] ?? null;
        $user         = $params['user'] ?? auth()->user(); // fallback to current
        $request      = $params['request'] ?? request();

        $userAgent = $request->header('User-Agent');
        $browserName = AuditHelper::detectBrowser($userAgent);

        AuditLog::create([
            'user_id'      => $user?->id,
            'action'       => $action,
            'target_type'  => $targetType,
            'target_id'    => $targetId,
            'metadata'     => $metadata,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'ip_address'   => $request->ip(),
            'user_agent'   => $browserName,
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function logAction($action, $tableName, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => Auth::id(), // ID de l'utilisateur connecté
            'action' => $action, // Action (ex : "validation", "modification")
            'table_name' => $tableName, // Table impactée (ex : "opportunities")
            'old_values' => $oldValues, // Les anciennes valeurs
            'new_values' => $newValues, // Les nouvelles valeurs
            'ip_address' => request()->ip(), // IP de l'utilisateur
        ]);
    }
}

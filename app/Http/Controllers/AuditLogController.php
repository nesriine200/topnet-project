<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
class AuditLogController extends Controller
{
public function index()
{
    // Récupérer tous les logs, triés par date de création
    $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(20);

    return view('audit_logs.index', compact('logs'));
}}

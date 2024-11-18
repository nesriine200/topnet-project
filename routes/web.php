<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:charge|apporteur'])->group(function () {
        Route::resource('opportunities', OpportunityController::class);
    });

    // Home Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Opportunity Management
    Route::put('opportunities/{id}/validate', [OpportunityController::class, 'validateOpportunity'])->name('opportunities.validate');
    Route::get('opportunities/print/{id}', [OpportunityController::class, 'print'])->name('opportunities.print');
    Route::get('contracts', [OpportunityController::class, 'contract'])->name('contract');

    // Offer Management
    Route::resource('offers', OfferController::class);

    // User Management
    Route::resource('users', UserController::class);
    Route::post('user/{userId}/assign-apporteurs', [UserController::class, 'assign_apporteurs']);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);
    // Route::get('users/{id}/show', [UserController::class, 'show'])->name('show');
    Route::get('/index', [UserController::class, 'index'])->name('index');

    // Notification Management
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');

    // Permission and Role Management
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('addPermission');
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

    // Chat Feature
    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/{user}', [ChatController::class, 'show']);
});

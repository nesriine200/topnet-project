<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // User Management
    Route::get('/index', [UserController::class, 'index'])->name('index');
    Route::get('/users/account-managers', [UserController::class, 'showAccountManagers'])->name('users.accountManagers');
    Route::get('/users/{user}/roles', [UserController::class, 'showRoles'])->name('users.roles');
    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assignRole');
    Route::post('user/{userId}/assign-apporteurs', [UserController::class, 'assign_apporteurs']);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);
    Route::resource('users', UserController::class);

    // Opportunity Management
    Route::resource('opportunities', OpportunityController::class);
    Route::get('opportunities/print/{id}', [OpportunityController::class, 'print'])->name('opportunities.print');
    Route::get('contracts', [OpportunityController::class, 'contract'])->name('contract');

    // Offer Management
    Route::resource('offers', OfferController::class);

    // Chat
    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/{user}', [ChatController::class, 'show']);

    // Role & Permission Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('permissions', PermissionController::class);
        Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

        Route::resource('roles', RoleController::class);
        Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
        Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole'])->name('addPermission');
        Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);
    });

    // Audit Logs & Validation (Charge & Admin Only)
    Route::middleware(['role:charge|admin'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');
        Route::put('opportunities/{id}/validate', [OpportunityController::class, 'validateOpportunity'])->name('opportunities.validate');
    });

    // Notifications (Apporteur Only)
    Route::middleware(['role:apporteur'])->group(function () {
        Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
        Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    });
});

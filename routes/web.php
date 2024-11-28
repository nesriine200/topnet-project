<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OpportunityController;
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

Route::middleware(['auth'])->group(function () {
    //opportunitie
    Route::resource('opportunities', OpportunityController::class);

    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Permission and Role Management
        Route::resource('permissions', App\Http\Controllers\PermissionController::class);
        Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
        Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('addPermission');
        Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

        // User Management
        Route::get('/users/account-managers', [UserController::class, 'showAccountManagers'])->name('users.accountManagers');
        Route::post('user/{userId}/assign-apporteurs', [UserController::class, 'assign_apporteurs']);
        Route::get('/users/{user}/roles', [UserController::class, 'showRoles'])->name('users.roles');
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assignRole');
        Route::get('/users/account-managers', [UserController::class, 'showAccountManagers'])->name('users.accountManagers');
    });
    Route::middleware(['auth', 'role:charge|admin'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');
        Route::put('opportunities/{id}/validate', [OpportunityController::class, 'validateOpportunity'])->name('opportunities.validate');
    });
    Route::middleware(['auth', 'role:charge|apporteur'])->group(function () {


        Route::middleware(['auth', 'role:apporteur'])->group(function () {
            // Notification Management
            Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
            Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
        });
    });
    // Chat Feature

    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/chat/{user}', [ChatController::class, 'show']);
    Route::resource('users', UserController::class);
    //home dashboard
    Route::get(
        '/home',
        [HomeController::class, 'index']
    )->name('home');
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);
    Route::get(
        '/index',
        [UserController::class, 'index']
    )->name('index');
    // Offer Management
    Route::resource('offers', OfferController::class);
    // Opportunity Management
    Route::get('opportunities/print/{id}', [OpportunityController::class, 'print'])->name('opportunities.print');
    Route::get('contracts', [OpportunityController::class, 'contract'])->name('contract');
});

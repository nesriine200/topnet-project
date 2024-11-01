<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Livewire\Chat;
Route::get('/chat/{user}', function (User $user) {
    return view('chat', compact('user'));
})->name('chat');
Route::get('/curl-test', function () {
    return var_dump(curl_version());
});
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
Route::get('/', function () {
    return view('welcome');
});
//Route::get('/chat',[Chat::class,'render'])->name('chat');

Route::resource('offers', OfferController::class);
Route::resource('opportunities', OpportunityController::class);
Route::put('opportunities/{id}/validate', [OpportunityController::class, 'validateOpportunity'])->name('opportunities.validate');
Route::get('opportunities/print/{id}', [OpportunityController::class, 'print'])->name('opportunities.print');
Route::get('/index', [UserController::class, 'index'])->name('index');
//Route::get('/home', [HomeController::class, 'index'])->name('index');
Auth::routes();
Route::get('/admin/dashboard', [opportunityController::class, 'statComissions'])->name('admin.dashboard');



Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');

Route::get('contracts', [App\Http\Controllers\OpportunityController::class, 'contract'])->name('contract');
Route::group(['middleware'=>'auth'],function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('auth/show', [App\Http\Controllers\UserController::class, 'show'])->name('show');
//    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('addPermission');
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);
    Route::resource('roles', App\Http\Controllers\RoleController::class);

    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy'])->middleware('permission:delete role');



});

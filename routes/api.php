<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.list');
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');


Route::get('/get-all-groups', [App\Http\Controllers\GroupController::class, 'getAllGroups'])->name('groups.getAllGroups');
Route::get('/groups', [App\Http\Controllers\GroupController::class, 'index'])->name('groups.list');
Route::post('/groups', [App\Http\Controllers\GroupController::class, 'store'])->name('groups.store');
Route::get('/groups/edit/{id}', [App\Http\Controllers\GroupController::class, 'edit'])->name('groups.edit');
Route::post('/groups/{id}', [App\Http\Controllers\GroupController::class, 'update'])->name('groups.update');
Route::delete('/groups/{id}', [App\Http\Controllers\GroupController::class, 'delete'])->name('groups.delete');

Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.list');
Route::post('/transactions', [App\Http\Controllers\TransactionController::class, 'store'])->name('transactions.store');
Route::get('/transactions/edit/{id}', [App\Http\Controllers\TransactionController::class, 'edit'])->name('transactions.edit');
Route::post('/transactions/{id}', [App\Http\Controllers\TransactionController::class, 'update'])->name('transactions.update');
Route::delete('/transactions/{id}', [App\Http\Controllers\TransactionController::class, 'delete'])->name('transactions.delete');

Route::get('/get-all-account-heads', [App\Http\Controllers\AccountHeadController::class, 'getAllAccountHeads'])->name('account-heads.getAllAccountHeads');
Route::get('/account-heads', [App\Http\Controllers\AccountHeadController::class, 'index'])->name('account-heads.list');
Route::post('/account-heads', [App\Http\Controllers\AccountHeadController::class, 'store'])->name('account-heads.store');
Route::get('/account-heads/edit/{id}', [App\Http\Controllers\AccountHeadController::class, 'edit'])->name('account-heads.edit');
Route::post('/account-heads/{id}', [App\Http\Controllers\AccountHeadController::class, 'update'])->name('account-heads.update');
Route::delete('/account-heads/{id}', [App\Http\Controllers\AccountHeadController::class, 'delete'])->name('account-heads.delete');
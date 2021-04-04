<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/getUsers', [App\Http\Controllers\AdminController::class, 'getUsers'])->name('getUsers');
Route::post('/saveUser', [App\Http\Controllers\AdminController::class, 'saveUser'])->name('saveUser');

Route::get('/getRoles', [App\Http\Controllers\AdminController::class, 'getRoles'])->name('getRoles');
Route::post('/saveRole', [App\Http\Controllers\AdminController::class, 'saveRole'])->name('saveRole');

Route::get('/getTicketStatuses', [App\Http\Controllers\AdminController::class, 'getTicketStatuses'])->name('getTicketStatuses');
Route::post('/saveTicketStatus', [App\Http\Controllers\AdminController::class, 'saveTicketStatus'])->name('saveTicketStatus');

// Admin Routes
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/admin/getUsers', [App\Http\Controllers\AdminController::class, 'users'])->name('admin');
Route::get('/admin/{any}',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.home')->where('any', '.*');


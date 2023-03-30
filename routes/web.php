<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\Branch_Users_Controller;


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
Route::get('/', function () {
    return view('sales.sales');
});

*/

// Sales Routes
Route::get('/', [SalesController::class, 'get_today_sales'])->middleware(['auth', 'verified'])->name('get_today_sales');
Route::get('/sales/{omega_id}', [SalesController::class, 'showBranchSales'])->middleware(['auth', 'verified'])->name('showBranchSales');
Route::get('/sales/{omega_id}/{eod_date}', [SalesController::class, 'showBranchSalesbyDate'])->middleware(['auth', 'verified'])->name('showBranchSalesbyDate');

// Branch Routes
Route::get('/branch/all', [BranchesController::class, 'get_all_branches'])->middleware(['auth', 'verified'])->name('get_all_branches');
Route::get('/branch/new', [BranchesController::class, 'create'])->middleware(['auth', 'verified'])->name('branches.create');
Route::post('/branch/store', [BranchesController::class, 'store'])->middleware(['auth', 'verified'])->name('branches.store');
Route::get('/branch/{id}/edit', [BranchesController::class, 'edit'])->middleware(['auth', 'verified'])->name('branches.edit');
Route::put('/branch/{id}/update', [BranchesController::class, 'update'])->middleware(['auth', 'verified'])->name('branches.update');

// Branch Users Routes
Route::get('/branch/users', [Branch_Users_Controller::class, 'get_all_branches_users'])->middleware(['auth', 'verified'])->name('branches.users');
Route::post('/branchuser/store', [Branch_Users_Controller::class, 'assign_branch_User'])->middleware(['auth', 'verified'])->name('assign.branchUser');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('logout', function ()
{
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

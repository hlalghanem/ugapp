<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\Branch_Users_Controller;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\http\Controllers\MyBranchesController;
use Illuminate\Support\Facades\Route;

// Sales Routes
Route::get('/', [SalesController::class, 'live_sales'])->middleware(['auth', 'verified'])->name('live_sales');
Route::get('/today', [SalesController::class, 'get_today_sales'])->middleware(['auth', 'verified'])->name('get_today_sales');

Route::get('/sales/{omega_id}', [SalesController::class, 'showBranchSales'])->middleware(['auth', 'verified','updateLastLogin'])->name('showBranchSales');
Route::get('/sales/{omega_id}/{eod_date}', [SalesController::class, 'showBranchSalesbyDate'])->middleware(['auth', 'verified'])->name('showBranchSalesbyDate');
Route::delete('/sales/delete_today_transactions/{omega_id}', [SalesController::class, 'deletetodaytranactions'])->middleware(['auth', 'verified'])->name('deletetodaytranactions');

// Branch Routes
Route::get('/branch/all', [BranchesController::class, 'get_all_branches'])->middleware(['auth', 'verified'])->name('get_all_branches');
Route::get('/branch/new', [BranchesController::class, 'create'])->middleware(['auth', 'verified'])->name('branches.create');
Route::post('/branch/store', [BranchesController::class, 'store'])->middleware(['auth', 'verified'])->name('branches.store');
Route::get('/branch/{id}/edit', [BranchesController::class, 'edit'])->middleware(['auth', 'verified'])->name('branches.edit');
Route::put('/branch/{id}/update', [BranchesController::class, 'update'])->middleware(['auth', 'verified'])->name('branches.update');
Route::delete('/branch/{id}/deletepayments', [BranchesController::class, 'deletepayments'])->middleware(['auth', 'verified'])->name('branches.deletepayments');
// My Branches Routes


// Branch Users Routes
Route::get('/branch/users', [Branch_Users_Controller::class, 'get_all_branches_users'])->middleware(['auth', 'verified'])->name('branches.users');
Route::post('/branchuser/store', [Branch_Users_Controller::class, 'assign_branch_User'])->middleware(['auth', 'verified'])->name('assign.branchUser');
Route::delete('/branchuser/delete/{user_id}/{branch_id}', [Branch_Users_Controller::class, 'delete_branch_User'])->middleware(['auth', 'verified'])->name('delete.branchUser');
Route::get('/myallbranches',[Branch_Users_Controller::class, 'showMyAllBranches'])->name('showMyAllBranches');
Route::put('/updateMyActiveBranches/{branchid}/{value}',[Branch_Users_Controller::class, 'updateMyActiveBranches'])->name('updateMyActiveBranches');
// Reports Routes
Route::get('/reports/salesbydate', [ReportsController::class, 'sales_report_by_date'])->middleware(['auth', 'verified'])->name('reports.bydate');
Route::get('/reports/salessummary', [ReportsController::class, 'sales_report_summary'])->middleware(['auth', 'verified'])->name('reports.salessummary');

//Users
Route::get('/users', [UsersController::class, 'showUsers'])->middleware(['auth', 'verified'])->name('users.showUsers');
Route::get('/users/setLanguageAr', [UsersController::class, 'setLanguageAr'])->middleware(['auth', 'verified'])->name('users.setLanguageAr');
Route::get('/users/setLanguageEn', [UsersController::class, 'setLanguageEn'])->middleware(['auth', 'verified'])->name('users.setLanguageEn');
Route::get('/user/{id}/edit', [UsersController::class, 'userPage'])->middleware(['auth', 'verified'])->name('user.edit');
Route::put('/user/{id}/update', [UsersController::class, 'userUpdate'])->middleware(['auth', 'verified'])->name('user.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('logout', function () {
    auth()->logout();
    Session()->flush();

    return redirect('/');

})->name('logout');

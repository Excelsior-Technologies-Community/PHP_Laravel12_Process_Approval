<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



/*
|--------------------------------------------------------------------------
| Approval System Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |------------------------------------------------
    | User Request Routes
    |------------------------------------------------
    */

    // Create Request Form
    Route::get('/approvals/create', [ApprovalController::class, 'create'])
        ->name('approvals.create');

    // Store Request
    Route::post('/approvals/store', [ApprovalController::class, 'store'])
        ->name('approvals.store');

    // View All Requests (My Requests)
    Route::get('/approvals', [ApprovalController::class, 'index'])
        ->name('approvals.index');


    /*
    |------------------------------------------------
    | Approver Routes
    |------------------------------------------------
    */

    // Pending Approvals
    Route::get('/approvals/pending', [ApproverController::class, 'pending'])
        ->name('approvals.pending');

    // Approve / Reject Action
    Route::post('/approvals/{id}/action', [ApproverController::class, 'action'])
        ->name('approvals.action');


    /*
    |------------------------------------------------
    | Approval History
    |------------------------------------------------
    */

    Route::get('/approvals/history/{id}', [ApproverController::class, 'history'])
        ->name('approvals.history');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
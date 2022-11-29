<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\Admin\AdminLoanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('requestLoan', [LoanController::class, 'requestLoan'])->name('loan.request');   
Route::middleware(['auth:sanctum'])->group(function () {
 
    Route::get('/user', function(Request $request) {
        return $request->user();
    });
    Route::post('requestLoan', [LoanController::class, 'requestLoan'])->name('loan.request'); 
    Route::get('getLoanList', [LoanController::class, 'getLoanList'])->name('loan.list'); 
    Route::post('rePayment', [LoanController::class, 'rePayment'])->name('loan.rePayment'); 

    //Admin Routes
    Route::middleware(['role:Admin'])->group(function () 
    {     
        Route::post('approveLoan', [AdminLoanController::class, 'approveLoan'])->name('loan.approve');       
        Route::get('pendingLoan', [AdminLoanController::class, 'pendingLoan'])->name('admin.loan.list'); 
    });      
});
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');   




<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function(){
    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::post('/login',[AuthController::class,'login'])->name('login');
});

Route::group(['prefix' => 'user','middleware' => ['jwt.verify']], function(){
    Route::get('/profile',[AuthController::class, 'profile'])->name('user.profile');
});

Route::group(['prefix' => 'finance','middleware' => ['jwt.verify']], function(){

    Route::group(['prefix' => 'account'], function(){
        Route::get('/',[AccountController::class, 'getAll'])->name('account.index');
        Route::post('/',[AccountController::class, 'create'])->name('account.create');
        Route::get('/{id}',[AccountController::class, 'detail'])->name('account.detail');
        Route::put('/{id}',[AccountController::class, 'update'])->name('account.update');
        Route::delete('/{id}',[AccountController::class, 'delete'])->name('account.delete');
        Route::post('/{id}/restore',[AccountController::class, 'restore'])->name('account.restore');
    });

    Route::get('/account-type',[AccountController::class, 'getAccountType'])->name('account.type');
    Route::get('/report',[ReportController::class, 'report'])->name('report');

    Route::group(['prefix' => 'transaction'], function(){
        Route::get('/',[TransactionController::class,'getAll'])->name('trx.index');
        Route::post('/',[TransactionController::class,'create'])->name('trx.create');
        Route::get('/{id}',[TransactionController::class,'detail'])->name('trx.detail');
        Route::put('/{id}',[TransactionController::class,'update'])->name('trx.update');
        Route::delete('/{id}',[TransactionController::class,'delete'])->name('trx.delete');
        Route::post('/{id}/restore',[TransactionController::class,'restore'])->name('trx.restore');
    });
});

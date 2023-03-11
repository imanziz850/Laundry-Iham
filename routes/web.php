<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogActivityController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [AuthController::class, 'formLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('profile', [ProfileController::class, 'update']);

    Route::middleware('can:admin')->group(function(){
        Route::resource('user',UserController::class);
        Route::resource('outlet', OutletController::class);
        Route::resource('paket', PaketController::class);
        Route::delete('log',[LogActivityController::class,'clear'])->name('log.clear');
    });

    Route::middleware('can:admin-kasir')->group(function(){
        Route::resource('member',MemberController::class);

        Route::get('transaksi',
            [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('transaksi/member/{member}', 
            [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('transaksi/member/{member}/add',
            [TransaksiController::class, 'add'])->name('transaksi.add');
        Route::get('transaksi/member/{member}/paket/{paket}/delete',
            [TransaksiController::class, 'delete'])->name('transaksi.delete');
        Route::get('transaksi/member/{member}/clear',
            [TransaksiController::class,'clear'])->name('transaksi.clear');
        Route::post('transaksi/member/{member}',
            [TransaksiController::class,'store'])->name('transaksi.store');
        Route::get('transaksi/{transaksi}',
            [TransaksiController::class,'detail'])->name('transaksi.detail');
        Route::put('transaksi/{transaksi}',
            [TransaksiController::class,'update'])->name('transaksi.update');
        Route::get('transaksi/member/{member}/paket/{paket}/updateCart',
            [TransaksiController::class,'updatecart'])->name('transaksi.updatecart');
        Route::get('transaksi/{transaksi}/status/{status}',
            [TransaksiController::class,'status'])->name('transaksi.status');
        Route::get('transaksi/{transaksi}/invoice',
            [TransaksiController::class,'invoice'])->name('transaksi.invoice');
    });

    Route::middleware('can:admin-owner')->group(function(){
        Route::get('/laporan',
            [LaporanController::class,'index'])->name('laporan.index');
        Route::get('/laporan/harian',
            [LaporanController::class,'harian'])->name('laporan.harian');
        Route::get('/laporan/perbulan',
            [LaporanController::class,'perbulan'])->name('laporan.perbulan');
        Route::get('log',[LogActivityController::class,'index'])->name('log');
       
    });  
});

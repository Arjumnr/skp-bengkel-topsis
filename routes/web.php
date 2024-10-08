<?php

use App\Http\Controllers\RuleController;
use App\Http\Controllers\TransactionsItemsController;
use Illuminate\Support\Facades\Route;
//return to view dashboard
// Route::get('/',  [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/logout' , [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::post('/login-post', [App\Http\Controllers\AuthController::class, 'postLogin'])->name('loginPost');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


Route::group(
    ['prefix' => '',  'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::group(
            ['prefix' => 'admin'],
            function () {
                
                
                        Route::resource('produk', 'ProductController');
                        Route::resource('transaksi', 'TransactionsController');
                        // Route::resource('transaksi-item', 'TransactionsItemsController');
                        Route::resource('transaksi-item', 'TransactionsItemsController')->only([
                            'index', 'create', 'store', 'edit', 'update', 'destroy'
                        ]);
                        Route::get('transaksi-item/rekomendasi/{id}', [TransactionsItemsController::class, 'rekomendasi'])->name('rekomendasi.index');

                        Route::resource('rule', 'RuleController')->only([
                            'index', 'create', 'store', 'edit', 'update', 'destroy'
                        ]);
                        // Custom route for frekuensi itemset 1
                        Route::get('rule/frekuensi-itemset-1', [RuleController::class, 'frekuensiItemset1'])->name('frekuensi-itemset-1.index');
                        Route::get('rule/support-minimum', [RuleController::class, 'supportMinimum'])->name('support-minimum.index');
                        Route::get('rule/kombinasi-itemset', [RuleController::class, 'kombinasiItemset'])->name('kombinasi-itemset.index');
                        Route::get('rule/hitung', [RuleController::class, 'hitung'])->name('hitung.index');
                        
                        
            }
        );
    }
);
// Route::group(['middleware' => ['auth']], function () {

//     Route::group(['middleware' => ['admin:1']], function () {
//         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//         Route::resource('users', UsersController::class);
//         Route::resource('jenis', JenisController::class);
//         Route::resource('antrian', AntrianAdminController::class);
//         Route::resource('barang', BarangController::class);
//         Route::resource('servis', ServisController::class);
//         Route::resource('penjualan', PenjualanController::class);
//         Route::resource('honor', HonorController::class);


            
//     });
// });

<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth Routes
Auth::routes();

Route::get('notifikasi', 'NotificationController@index')->middleware('auth');
Route::get('kirim-email', 'TestEmailController@kirimEmail')->name('kirim.email');

// Home Route
Route::get('', 'HomeController@index')->name('home');
Route::get('profile', 'ProfileController@show')->name('profile.show');
Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
Route::get('profile/edit/{id}', 'ProfileController@editAdmin')->name('profile.editAdmin'); // Untuk admin edit user lain
Route::put('profile/update', 'ProfileController@update')->name('profile.update');
Route::put('profile/password/update', 'ProfileController@updatePassword')->name('profile.password.update');

// Navbar Route
Route::get('navbar', 'NavbarController@index')->name('navbar');

// Produk Routes
Route::get('produks', 'ProdukController@index')->name('produks');
Route::get('produks/tambah', 'ProdukController@tambah')->name('produks.tambah');
Route::post('produks/simpan', 'ProdukController@simpan')->name('produks.simpan');
Route::get('produks/{id}/edit', 'ProdukController@edit')->name('produks.edit');
Route::put('produks/update/{id}', 'ProdukController@update')->name('produks.update');
Route::delete('produks/delete/{id}', 'ProdukController@delete')->name('produks.delete');

// // Produk Routes
// Route::get('toppings', 'ToppingController@index')->name('toppings');
// Route::get('toppings/tambah', 'ToppingController@tambah')->name('toppings.tambah');
// Route::post('toppings/simpan', 'ToppingController@simpan')->name('toppings.simpan');
// Route::get('toppings/{id}/edit', 'ToppingController@edit')->name('toppings.edit');
// Route::put('toppings/update/{id}', 'ToppingController@update')->name('toppings.update');
// Route::delete('toppings/delete/{id}', 'ToppingController@delete')->name('toppings.delete');

// Route::get('toppings/menu/{menuId}', 'ToppingController@index')->name('toppings.menu'); // untuk topping berdasarkan menu
// Route::get('toppings/{id}', 'ToppingDetailController@index')->name('topping.detail'); // detail topping
// Route::post('toppings/{id}/add-to-cart', 'ToppingDetailController@addToCart')->name('topping.addToCart'); // menambah produk ke keranjang
Route::get('produks/menu/{menuId}', 'ProdukController@index')->name('produks.menu'); // untuk produk berdasarkan menu
Route::get('produks/{id}', 'ProdukDetailController@index')->name('produk.detail'); // detail produk
Route::post('produks/{id}/add-to-cart', 'ProdukDetailController@addToCart')->name('produk.addToCart'); // menambah produk ke keranjang
Route::get('keranjang', 'KeranjangController@index')->name('keranjang');
Route::delete('keranjang/{pesanan_detail}', 'KeranjangController@hapus')->name('keranjang.hapus');
Route::put('keranjang/update/{pesanan_detail}', 'KeranjangController@update')->name('keranjang.update');
Route::get('chekout', 'CheckoutController@index')->name('checkout');
Route::post('bayar', 'CheckoutController@bayar')->name('bayar');
Route::get('checkout/sukses', 'CheckoutController@sukses')->name('checkout.sukses');
Route::post('update-bayar', 'CheckoutController@updateStatusPesanan')->name('update.bayar');
Route::get('history', 'CheckoutSuksesController@index')->name('checkout.sukses');

// Actual route
Route::get('actual', 'ActualController@index')->name('actual');
Route::get('actual/tambah', 'ActualController@tambah')->name('ac.t');
Route::post('actual/simpan', 'ActualController@simpan')->name('ac.s');
Route::get('actual/{id}/edit', 'ActualController@edit')->name('ac.e');
Route::patch('actual/{id}', 'ActualController@update')->name('ac.u');
Route::delete('actual/{id}', 'ActualController@delete')->name('ac.d');
Route::get('actual/bar', 'ActualController@chartactual')->name('ac.bar');

// Gaji Aplikasi route
Route::get('gaji', 'GajiController@index')->name('gaji');
Route::get('gaji/exportgaji', 'GajiController@gajiexport')->name('gaji.export');
Route::get('gaji/edit/{id}', 'GajiController@edit')->name('gaj.e');
Route::post('gaji/store', 'GajiController@store')->name('gaji.store');
Route::put('gaji/update/{id}', 'GajiController@update')->name('gaji.update');
Route::delete('gaji/destroy/{id}', 'GajiController@destroy')->name('gaji.destroy');
Route::get('gaji/print', 'GajiController@printall')->name('gaji.printall');
Route::get('invoice/{id}', 'GajiController@print')->name('gaji.invoice');
Route::get('getJabatanData/{karyawanId}', 'GajiController@getJabatanData');


// Divisi route
Route::get('divisi', 'DivisiController@index')->name('divisi');
Route::get('divisi/edit/{id}', 'DivisiController@edit')->name('div.e');
Route::post('divisi/store', 'DivisiController@store')->name('divisi.store');
Route::delete('divisi/destroy/{id}', 'DivisiController@destroy')->name('divisi.destroy');
Route::put('divisi/update/{id}', 'DivisiController@store')->name('divisi.update');

// Divisi route
Route::get('absensi', 'AbsensiController@index')->name('absensi');
Route::get('absensi/edit/{id}', 'AbsensiController@edit')->name('absensi.edit');
Route::post('absensi/store', 'AbsensiController@store')->name('absensi.store');
Route::put('absensi/update/{id}', 'AbsensiController@store')->name('absensi.update');
Route::delete('absensi/destroy/{id}', 'AbsensiController@destroy')->name('absensi.destroy');


// Karyawan route
Route::get('karyawan', 'KaryawanController@index')->name('karyawan');
Route::get('karyawan/edit/{id}', 'KaryawanController@edit')->name('karyawan.edit');
Route::get('karyawan/exportkaryawan', 'KaryawanController@karyawanexport')->name('karyawan.export');
Route::post('karyawan/store', 'KaryawanController@store')->name('karyawan.store');
Route::put('karyawan/update/{id}', 'KaryawanController@update')->name('karyawan.update');
Route::delete('karyawan/destroy/{id}', 'KaryawanController@destroy')->name('karyawan.destroy');
Route::get('getJabatanAndDivisi/{karyawan}', 'KaryawanController@getJabatanAndDivisi');

// Jabatan route
Route::get('jabatan', 'JabatanController@index')->name('jabatan');
Route::get('jabatan/edit/{id}', 'JabatanController@edit')->name('jab.e');
Route::post('jabatan/store', 'JabatanController@store')->name('jabatan.store');
Route::put('jabatan/update/{id}', 'JabatanController@store')->name('jabatan.update');
Route::delete('jabatan/destroy/{id}', 'JabatanController@destroy')->name('jabatan.destroy');
Route::get('getJabatanDetail/{id}', 'JabatanController@getJabatanDetail')->name('jabatan.detail');

// Laporan
Route::get('laporan', 'LaporanController@index')->name('laporan');
Route::get('laporan/exportlaporan', 'LaporanController@laporanexport')->name('laporan.export');
Route::post('laporan/importlaporan', 'LaporanController@laporanimport')->name('laporan.import');
Route::get('laporan/data', 'LaporanController@laporanData')->name('laporan.data');
Route::get('laporan/print', 'LaporanController@print')->name('laporan.print');
Route::get('laporan/print-all', 'LaporanController@printAll')->name('laporan.printAll');
Route::get('laporan/doughnut', 'LaporanController@chartlaporan')->name('laporan.doughnut');


Route::get('laporanbar', 'LaporanbarController@index')->name('laporan.bar');
Route::post('laporanbar', 'LaporanbarController@filterPendapatan')->name('laporan.bar');

Route::get('favitem', 'ProdukStatistikController@index')->name('favitem');

// Route::post('debug-store', function (\Illuminate\Http\Request $request) {
//     Log::info('Data diterima di /debug-store: ' . json_encode($request->all()));
//     return response()->json(['success' => true, 'data' => $request->all()]);
// });
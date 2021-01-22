<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AjaxPController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImportController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/siswa', [RoleController::class,'siswa'])->middleware('role:user');
Route::get('/admin',  [RoleController::class,'admin'])->middleware('role:admin');
Route::get('/pengajar',  [RoleController::class,'pengajar'])->middleware('role:pengajar');
Route::get('/redirect',  [RoleController::class,'index']);
Route::get('logout', [RoleController::class,'logout']);
Route::get('ip_details', 'UserController@ip_details');
//Pengajar
Route::get('/pengajar/action/absen/materi/{materi}/{kelas}', [AjaxPController::class,'materi']);
Route::post('/pengajar/action/absen', [AjaxPController::class,'absen']);
Route::get('/pengajar/action/absen/absenkeluar/{id_masuk}', [AjaxPController::class,'absen_keluar'])->middleware('role:pengajar');

//ADmin
Route::get('/pengajar/action/absen/absenadmin/{id_masuk}/{id_pengajar}', [AjaxPController::class,'absen_konfirm'])->middleware('role:admin');
Route::get('/admin/riwayat', [AdminController::class,'riwayat'])->middleware('role:admin');
Route::get('/admin/riwayat/data', [AdminController::class,'riwayatData'])->middleware('role:admin');
Route::get('/admin/pantaukelas/', [AdminController::class,'pantau'])->middleware('role:admin');
Route::post('/admin/pantaukelas/data', [AdminController::class,'pantauData'])->middleware('role:admin');
Route::get('/admin/taksiran/', [AdminController::class,'taksiran'])->middleware('role:admin');
Route::post('/admin/taksiran/data', [AdminController::class,'taksiranData'])->middleware('role:admin');
Route::get('/admin/pendataan', [AdminController::class,'pendataan'])->middleware('role:admin');
Route::get('/admin/siswa/data', [AdminController::class,'siswaData'])->middleware('role:admin');
Route::post('/admin/import/siswa', [ImportController::class,'import_siswa'])->middleware('role:admin');
Route::get('/admin/penilaian', [AdminController::class,'penilaian'])->middleware('role:admin');
Route::get('/admin/nilai/data', [AdminController::class,'nilaiData'])->middleware('role:admin');
Route::post('/admin/import/penilaian', [ImportController::class,'import_penilaian'])->middleware('role:admin');
/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/
Route::get('/dashboard',  [RoleController::class,'index']);

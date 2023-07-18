<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JenisSuratController;
use App\Http\Controllers\API\SuratKeluarController;
use App\Http\Controllers\API\SuratMasukController;
use App\Http\Controllers\API\TahunController;
use App\Models\JenisDokumenModel;
use App\Models\JenisSuratModel;
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
*/


//auth
Route::prefix('v4')->controller(AuthController::class)->group(function () {
    Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/auth/verify-email/{email}', 'verifyEmail');
    Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/auth/register', 'register');
    Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/auth/login', 'login');
});



Route::middleware('web', 'auth')->group(function () {
    Route::get('/', function () {
        return view('backend.dashboard');
    });

    Route::get('/cms/tahun', function () {
        return view('backend.tahun');
    });
    Route::get('/cms/surat/masuk', function () {
        return view('backend.surat-masuk');
    });
    Route::get('/cms/jenis/surat', function () {
        return view('backend.jenis-surat');
    });

    Route::get('/cms/arsip/{uuid}', function () {
        return view('backend.arsip');
    });
    Route::get('/cms/arsip/{id_tahun}/{id_jenis_surat}', function () {
        return view('backend.arsip-surat-masuk');
    });

    Route::get('/cms/surat/keluar', function () {
        return view('backend.surat-keluar');
    });



    //tahun arsip
    Route::prefix('v1')->controller(TahunController::class)->group(function () {
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/tahun', 'getAllData');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/tahun/create', 'createData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/tahun/get/{uuid}', 'getDataByUuid');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/tahun/update/{uuid}', 'updateDataByUuid');
        Route::delete('/396d6585-16ae-4d04-9549-c499e52b75ea/tahun/delete/{uuid}', 'deleteData');
    });

    //jenis surat
    Route::prefix('v2')->controller(JenisSuratController::class)->group(function () {
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat', 'getAllData');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/create', 'createData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/get/{uuid}', 'getDataByUuid');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/get/id/{id}', 'getDataById');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/update/{uuid}', 'updateDataByUuid');
        Route::delete('/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/delete/{uuid}', 'deleteData');
    });

    //surat masuk 
    Route::prefix('v3')->controller(SuratMasukController::class)->group(function () {
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk', 'getAllData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/user/{id_tahun}/{id_jenis_surat}', 'getDataByUser');
        // Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/get/{id_tahun}/{id_jenis_surat}', 'getDataByTahunAndJenisSurat');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/create', 'createData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/get/{uuid}', 'getDataByUuid');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/update/{uuid}', 'updateDataByUuid');
        Route::delete('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/delete/{uuid}', 'deleteData');
    });

    Route::prefix('v4')->controller(SuratKeluarController::class)->group(function () {
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar', 'getAllData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/user/{id_tahun}/{id_jenis_surat}', 'getDataByUser');
        // Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/get/{id_tahun}/{id_jenis_surat}', 'getDataByTahunAndJenisSurat');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/create', 'createData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/get/{uuid}', 'getDataByUuid');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/update/{uuid}', 'updateDataByUuid');
        Route::delete('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-keluar/delete/{uuid}', 'deleteData');
    });
});


Route::get('/login', function () {
    return view('auth.login');
});

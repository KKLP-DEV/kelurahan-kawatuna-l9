<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\JenisSuratController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SuratController;
use App\Http\Controllers\API\TahunController;
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
    Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/auth/logout', 'logout');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    });
});






Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('backend.dashboard');
    });

    Route::get('/cms/setting', function () {
        return view('backend.setting');
    });

    Route::post('/cms/setting', function () {
        return view('backend.setting');
    });

    Route::get('/cms/tahun', function () {
        return view('backend.tahun');
    })->middleware('adminlevel:1');

    Route::post('/cms/tahun', function () {
        return view('backend.tahun');
    })->middleware('adminlevel:1');

    Route::get('/cms/surat', function () {
        return view('backend.surat');
    });
    Route::post('/cms/surat', function () {
        return view('backend.surat');
    });

    Route::get('/cms/jenis/surat', function () {
        return view('backend.jenis-surat');
    })->middleware('adminlevel:1');
    
    Route::post('/cms/jenis/surat', function () {
        return view('backend.jenis-surat');
    })->middleware('adminlevel:1');

    Route::get('/cms/arsip/surat/get/{id}', function () {
        return view('backend.arsip');
    });

    Route::get('/cms/arsip/surat/get/data/{id_tahun}/{id_jenis_surat}', function () {
        return view('backend.arsip-surat');
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

    //surat arsip 
    Route::prefix('v3')->controller(SuratController::class)->group(function () {
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip', 'getAllData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/user/{id_tahun}/{id_jenis_surat}', 'getDataByUser');
        // Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/get/{id_tahun}/{id_jenis_surat}', 'getDataByTahunAndJenisSurat');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/create', 'createData');
        Route::get('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/get/{uuid}', 'getDataByUuid');
        Route::post('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/update/{uuid}', 'updateDataByUuid');
        Route::delete('/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/delete/{uuid}', 'deleteData');
    });


    Route::get('/dashboard/get/count', [DashboardController::class, 'countData']);
    Route::get('/profile/get/', [ProfileController::class, 'getProfile']);

    Route::Post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/get/user', [AuthController::class, 'getDataUser']);
});


Route::get('/register', function () {
    return view('auth.register');
});

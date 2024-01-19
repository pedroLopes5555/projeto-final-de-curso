<?php

use App\Api\CoreApi;
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

Route::get('/', function () {
    return view('index');
});

Route::get('/details', function(){
    return view('portfolio-details');
});

/*****************BACK************************/

Route::get('/login', function(){
    return view('admin.login');
})->name('route.login');

Route::middleware('perms:autorized')->group(function () {

    Route::get('/admin', function(){
        return view('admin.index');
    })->name('routeHomepage');

    Route::get('/admin/containers', function(){
        return view('admin.containers.containers');
    });

    Route::get('/admin/arduinos', function(){
        return view('admin.containers.arduinos');
    });

    Route::get('/admin/sensors', function(){
        return view('admin.sensors.sensors');
    });

});

Route::middleware('perms:users')->group(function () {

    Route::get('/admin/users', function(){
        return view('admin.users.users');
    });

    Route::get('/admin/perms', 'UserController@perms');

});

Route::get('/sendTest', function () {
    $coreApi = new CoreApi();
    $response = $coreApi->sendTest();
    return response()->json($response);
});
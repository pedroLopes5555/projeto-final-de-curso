<?php

use App\Api\CoreApi;
use App\Models\Rele;
use App\Models\Sensor;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\SensorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*-----------------------------------Login-----------------------------------*/

Route::post('/login', 'LoginController@login')->name('login');
Route::post('/logout', 'LoginController@logout')->name('logout');

/*-----------------------------------Select----------------------------------*/

Route::get('/select/arduinos', 'SelectController_api@arduinos');
Route::get('/select/perms', 'SelectController_api@perms');
Route::get('/select/containers', 'SelectController_api@containers');

/************************************User ************************************/

Route::post('/admin/user/new', 'UserController@create');
Route::post('/admin/user/update', 'UserController@update');
Route::post('/admin/user/delete', 'UserController@delete');

Route::post('/admin/perm/new', 'UserController@newPerm');
Route::post('/admin/perm/update', 'UserController@updatePerm');
Route::post('/admin/perm/delete', 'UserController@deletePerm');

/*-----------------------------------Table-----------------------------------*/

Route::post('/admin/table/containers', 'TableController_api@containers');
Route::post('/admin/table/arduinos', 'TableController_api@arduinos');
Route::post('/admin/table/sensors', 'TableController_api@sensors');
Route::post('/admin/table/users', 'TableController_api@users');
Route::post('/admin/table/perms', 'TableController_api@perms');
Route::post('/admin/table/container/{container_id}', 'TableController_api@container');
/*****************Containers*******************************/

Route::post('/admin/container/new', 'ContainerController@create');
Route::post('/admin/container/update', 'ContainerController@update');
Route::post('/admin/container/delete', 'ContainerController@delete');
Route::post('/admin/container/activate', 'ContainerController@activate');

/*****************Microcontroller*******************************/

Route::post('/admin/micro/update', 'ArduinoController@update');
Route::post('/admin/microcontroller/new', 'ArduinoController@new');
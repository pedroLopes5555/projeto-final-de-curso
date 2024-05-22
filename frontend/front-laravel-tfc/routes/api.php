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

/************************************User ************************************/

Route::post('/user/create', 'UserController@create');
Route::post('/user/update', 'UserController@update');
Route::post('/user/delete', 'UserController@delete');

Route::post('/admin/perm/new', 'UserController@newPerm');
Route::post('/admin/perm/update', 'UserController@updatePerm');
Route::post('/admin/perm/delete', 'UserController@deletePerm');

/*-----------------------------------Table-----------------------------------*/

Route::post('/admin/table/containers', 'TableController_api@containers');
Route::post('/admin/table/arduinos', 'TableController_api@arduinos');
Route::post('/admin/table/sensors', 'TableController_api@sensors');
Route::post('/admin/table/users', 'TableController_api@users');
Route::post('/admin/table/perms', 'TableController_api@perms');

/*****************Containers*******************************/

Route::post('/admin/dashboard/containers', 'ApplicationController@containers')->name('dashboard.store');

/*-----------------------------------API-------------------------------------*/

Route::post('/sendTest', function () {
    $coreApi = new CoreApi();
    $response = $coreApi->sendTest();

    // json response
    return response()->json($response);
});

Route::post('/admin/RequestContainers', function () {
    
    $coreApi = new CoreApi();
    $response = $coreApi->RequestContainers('8EB08444-2B3A-4BDD-A85E-90337AAB11F1');

    $containersToSaveIds = [];

    // get login user 
    $user = session('user');

    foreach ($response as $containerData) {

        $containersToSaveIds[] = $containerData['id'];

        // Check if id exists in database
        $container = Container::where('container_serial', $containerData['id'])->first();
  
        // if container exists, update it
        if ($container) {
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
            $container->user_id = $user;
            $container->save();
        } else {
            // Create Container
            $container = new Container();
            $container->container_serial = $containerData['id'];
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
            $container->user_id = $user;
            $container->save();
        }

        // Get the container ID
        $containerId = $container->container_id;

        // Iterate over microcontrollers if not null
        if (!isset($containerData['microcontrollers'])) {
            continue;
        }

        foreach ($containerData['microcontrollers'] as $microcontrollerData) {
            $arduino = new Arduino();
            $arduino->arduino_name = $microcontrollerData['name'];
            $arduino->arduino_capacity = $microcontrollerData['capacity'];
            $arduino->container_id = $containerId; // Set container_id
            $arduino->save();

            // Get the arduino ID
            $arduinoId = $arduino->arduino_id;

            // Iterate over relays
            foreach ($microcontrollerData['relays'] as $relayData) {
                $rele = new Rele();
                $rele->rele_name = $relayData['name'];
                $rele->rele_state = $relayData['state'];
                $rele->arduino_id = $arduinoId; // Set arduino_id
                $rele->save();

                // Save Sensor
                $sensorType = SensorType::firstOrCreate(['sensor_type_name' => $relayData['sensor']['type']]);
                $sensor = new Sensor();
                $sensor->sensor_name = $relayData['sensor']['name'];
                $sensor->sensor_type_id = $sensorType->sensor_type_id;
                $sensor->rele_id = $rele->rele_id; // Set rele_id
                $sensor->save();
            }
        }
    }

    // Delete containers that are not in the response
    Container::whereNotIn('container_serial', $containersToSaveIds)->delete();

    return response()->json($response);
});
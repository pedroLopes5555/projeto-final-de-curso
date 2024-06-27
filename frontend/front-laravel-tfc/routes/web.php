<?php

use App\Api\CoreApi;
use App\Models\Rele;
use App\Models\User;
use App\Api\LoginApi;
use App\Models\Sensor;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\SensorType;
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

    Route::get('/admin', 'ApplicationController@index')->name('routeHomepage');
    Route::get('/admin/containers', function(){
        return view('admin.containers.containers');
    });

    Route::get('/admin/arduinos', function(){
        return view('admin.containers.arduinos');
    });

    Route::get('/admin/sensors', function(){
        return view('admin.sensors.sensors');
    });

    Route::get('/saveData', function(){
        $api = new CoreApi();
        $response = $api->RequestContainers();
    
        foreach ($response as $containerData) {
            // Create Container
            $container = new Container();
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
            $container->save();
    
            // Get the container ID
            $containerId = $container->container_id;
    
            // Iterate over microcontrollers
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
    });

    Route::get('/admin/containers/{container_id}', function($container_id){

        $user = User::getCurrent();
        $container = Container::where('container_id', $container_id)->where('user_id', $user->user_id)->first();

        if(!$container){
            return response()->json(['status' => 'error', 'message' => 'Container not found']);
        }
        
        if($container->user_id != $user->user_id){
            return response()->json(['status' => 'error', 'message' => 'Container not found']);
        }

        

        return view('admin.containers.container', ['container' => $container]);

    });

});

Route::middleware('perms:users')->group(function () {

    Route::get('/admin/users', function(){
        return view('admin.users.users');
    });

    Route::get('/admin/perms', 'UserController@perms');

});
Route::get('/testLogin', function(){
    $api = new LoginApi();
    $response = $api->login('ola123', 'teste13');
    return response()->json($response);
});
Route::get('/sendTest', function () {
    $coreApi = new CoreApi();
    $response = $coreApi->sendTest();

    return response()->json($response);
});

Route::get('/RequestContainers', function () {
    $coreApi = new CoreApi();
    $response = $coreApi->RequestContainers('8EB08444-2B3A-4BDD-A85E-90337AAB11F1');

    $containersToSaveIds = [];

    foreach ($response as $containerData) {

        $containersToSaveIds[] = $containerData['id'];

        // Check if id exists in database
        $container = Container::where('container_serial', $containerData['id'])->first();
  
        // if container exists, update it
        if ($container) {
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
            $container->save();
        } else {
            // Create Container
            $container = new Container();
            $container->container_serial = $containerData['id'];
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
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
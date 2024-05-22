<?php

namespace App\Http\Controllers;

use App\Api\CoreApi;
use App\Models\Rele;
use App\Models\Sensor;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\SensorType;
use App\Models\TargetValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    function saveContainersData($data) {
        foreach ($data as $containerData) {
            // Create Container
            $container = new Container();
            $container->container_name = $containerData['name'];
            $container->container_dimension = $containerData['dimension'];
            $container->container_location = $containerData['location'];
            $container->save();
    
            // Iterate over microcontrollers
            foreach ($containerData['microcontrollers'] as $microcontrollerData) {
                $arduino = new Arduino();
                $arduino->arduino_name = $microcontrollerData['name'];
                $arduino->arduino_capacity = $microcontrollerData['capacity'];
                $arduino->container_id = $container->id;
                $arduino->save();
    
                // Iterate over relays
                foreach ($microcontrollerData['relays'] as $relayData) {
                    $rele = new Rele();
                    $rele->rele_name = $relayData['name'];
                    $rele->rele_state = $relayData['state'];
                    $rele->arduino_id = $arduino->id;
                    $rele->save();
    
                    // Save Sensor
                    $sensorType = SensorType::firstOrCreate(['sensor_type_name' => $relayData['sensor']['type']]);
                    $sensor = new Sensor();
                    $sensor->sensor_name = $relayData['sensor']['name'];
                    $sensor->sensor_type_id = $sensorType->id;
                    $sensor->rele_id = $rele->id;
                    $sensor->save();
                }
            }
        }
    }

    function index(){
        // current user 
        $user = session('user');


        // get all containers that belongs to the user
        $containers = Container::where('user_id', $user)->with('targetValue')->get();

        return view('admin.index', ['containers' => $containers]);
        
    }

    function containers(Request $request){

        // check if container already has a desired value, if so, update it
        $target = TargetValue::where('container_id', $request->container_id)->first();
        if ($target) {
            $target->value_ph = $request->value_ph;
            $target->value_temp = $request->value_temp;
            $target->value_electric_condutivity = $request->value_electric_condutivity;
            $target->save();
        } else {
            $target = new TargetValue();
            $target->value_ph = $request->value_ph;
            $target->value_temp = $request->value_temp;
            $target->value_electric_condutivity = $request->value_electric_condutivity;
            $target->container_id = $request->container_id;
            $target->save();
        }

        // get Container by id
        $container = Container::find($request->container_id);
        // api call to update it in the backend
        $api = new CoreApi();
        $api->setDesiredValue($container->container_serial, $request->value_ph, 1);
        $api->setDesiredValue($container->container_serial, $request->value_temp, 4);
        $api->setDesiredValue($container->container_serial, $request->value_electric_condutivity, 2);
        // refresh the page
        return redirect()->route('admin.index');
    }
}

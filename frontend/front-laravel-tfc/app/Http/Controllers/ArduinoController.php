<?php

namespace App\Http\Controllers;

use App\Api\ArduinoApi;
use App\Api\ContainerApi;
use App\Api\CoreApi;
use App\Models\Rele;
use App\Models\User;
use App\Models\Sensor;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\SensorType;
use App\Models\TargetValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArduinoController extends Controller
{

    
    function update(Request $request){
        
        $api = new ArduinoApi();
        $arduino = Arduino::find($request->arduino_id);
        $container = Container::find($request->container_id);
        $response = $api->AddMicrocontrollerToContainer($arduino->arduino_guid, $container->container_guid);

        if(!$response){
            return response()->json([
                'message' => 'Arduino not found',
            ], 404);
        }

        // change arduino container 
        $arduino->container_id = $request->container_id;
        $arduino->save();

        return response()->json([
            'message' => 'Container updated successfully',
        ], 201);
    }

    function new(Request $request){

        $api = new ArduinoApi();

        $user = User::find($request->user_id);
        $response = $api->CreateMicrocontroller($user->user_guid, $request->microcontroller_guid, $request->microcontroller_name);

        if(!$response){
            return response()->json([
                'message' => 'Arduino not found',
            ], 404);
        }

        $arduino = new Arduino();
        $arduino->arduino_name = $request->microcontroller_name;
        $arduino->arduino_guid = $request->microcontroller_guid;
        $arduino->user_id = $request->user_id;
        $arduino->save();

        return response()->json([
            'message' => 'Arduino created successfully',
        ], 201);
    }

}

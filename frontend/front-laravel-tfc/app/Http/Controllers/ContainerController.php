<?php

namespace App\Http\Controllers;

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

class ContainerController extends Controller
{

    function create(Request $request){

        $user = User::getCurrent();

        $api = new ContainerApi();

        $response = $api->CreateNewContainer($request->container_name, $request->container_location, $user->user_guid);

        if(!$response['containerCreated']){
            return response()->json(['status' => 'error', 'message' => 'Error creating user']);
        }

        $guid = $response['id'];


        $arrayValues = [
            'ph' => [
                'value' => floatval($request->value_ph),
                'margin' => 0.5,
                'actionTime' => 100000,
                'valueType' => 1
            ],
            'ec' => [
                'value' => floatval($request->value_electric_condutivity),
                'margin' => 0.5,
                'actionTime' => 100000,
                'valueType' => 2,
            ],
            'temperature' => [
                'value' => floatval($request->value_temp),
                'margin' => 0.5,
                'actionTime' => 100000,
                'valueType' => 3,
            ]
        ];


        foreach($arrayValues as $key => $value){
            $response = $api->SetContainerConfig($guid, $value['valueType'], $value['value'], $value['margin'], $value['actionTime']);
        }

        
        $container = new Container();
        $container->container_name = $request->container_name;
        $container->container_location = $request->container_location;
        $container->container_guid = $guid;
        $container->user_id = $user->user_id;

        $container->save();

        $TargetValue = new TargetValue();
        $TargetValue->container_id = $container->container_id;
        $TargetValue->value_ph = $request->value_ph;
        $TargetValue->value_electric_condutivity = $request->value_electric_condutivity;
        $TargetValue->value_temp = $request->value_temp;

        $TargetValue->save();

        return response()->json([
            'message' => 'Container created successfully',
            'container' => $container
        ], 201);

    }

    function update(Request $request){

        $container = Container::find($request->container_id);
        $api = new ContainerApi();

        $response = $api->EditContainer($container->container_guid, $request->container_name, $request->container_location);

        if(!$response){
            return response()->json([
                'message' => 'Container not found',
            ], 404);
        }
        
        $container->container_name = $request->container_name;
        $container->container_location = $request->container_location;

        $container->save();

        return response()->json([
            'message' => 'Container updated successfully',
            'container' => $container
        ], 201);
    }

    function delete(Request $request){

        $container = Container::find($request->container_id);
        $api = new ContainerApi();

        $response = $api->DeleteContainer($container->container_guid);

        if(!$response){
            return response()->json([
                'message' => 'Container not found',
            ], 404);
        }

        $arduinos = Arduino::where('container_id', $request->container_id)->get();
        foreach($arduinos as $arduino){
            $arduino->container_id = null;
            $arduino->save();
        }

        $targetValue = TargetValue::where('container_id', $request->container_id)->first();
        
        if($targetValue){
            $targetValue->delete();
        }
        $container = Container::find($request->container_id);
        $container->delete();

        return response()->json([
            'message' => 'Container deleted successfully',
            'container' => $container
        ], 201);
    }



}

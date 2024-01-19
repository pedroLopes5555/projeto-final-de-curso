<?php
namespace App\Api;

use App\Api\BaseApi;
use App\Models\Rele;
use App\Models\Value;
use App\Models\Sensor;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\SensorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CoreApi extends BaseApi {

    protected $base_url = 'https://hydrogrowthmanager.azurewebsites.net/automation/';

    public function sendTest(){
        return $this->post('SendTest', ['micrcocontrollerID' => 'AA:BB:CC', 'type' => 'TDS', 'value' => '102.2']);
    }

    /*
    {
    "container": {
        "container_name": "Container 1",
        "container_dimension": 3,
        "container_location": "Location 1"
    },
    "arduino": {
        "arduino_name": "Arduino 1",
        "arduino_capacity": 10
    },
    "rele": {
        "rele_name": "Rele 1",
        "rele_state": true
    },
    "sensor": {
        "sensor_name": "Sensor 1",
        "sensor_type": "pH"
    },
    "value": {
        "value_ph": 7.0,
        "value_temp": 25.5,
        "value_electric_conductivity": 300.5,
        "value_time": "2023-11-23 12:00:00"
    }
}
    */

    public function storeData(Request $request)
    {
        // Validate the incoming request data as needed

        $containerData = $request->input('container');
        $arduinoData = $request->input('arduino');
        $releData = $request->input('rele');
        $sensorData = $request->input('sensor');
        $valueData = $request->input('value');


        \DB::beginTransaction();

        try {
            // Create and save Container
            $container = Container::create($containerData);

            // Create and save Arduino with foreign key relation to Container
            $arduinoData['container_id'] = $container->container_id;
            $arduino = Arduino::create($arduinoData);

            // Create and save Rele with foreign key relation to Arduino
            $releData['arduino_id'] = $arduino->arduino_id;
            $rele = Rele::create($releData);

            // Create and save SensorType
            $sensorType = SensorType::create(['sensor_type_name' => $sensorData['sensor_type']]);

            // Create and save Sensor with foreign key relations to SensorType and Rele
            $sensorData['sensor_type_id'] = $sensorType->sensor_type_id;
            $sensorData['rele_id'] = $rele->rele_id;
            $sensor = Sensor::create($sensorData);

            // Create and save Value with foreign key relation to Container
            $valueData['container_id'] = $container->container_id;
            $valueData['value_time'] = \Carbon\Carbon::parse($valueData['value_time']);
            $value = Value::create($valueData);

            // Commit the transaction
            \DB::commit();

            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            
            \DB::rollBack();

            return response()->json(['error' => 'Failed to save data. ' . $e->getMessage()], 500);
        }
    }

    public function getContainerData($container_id)
    {
        $container = Container::find($container_id);

        if (!$container) {
            return response()->json(['error' => 'Container not found'], 404);
        }

        $container->load('arduino.rele.sensor.sensorType', 'values');

        return response()->json($container);
    }

    public function getContainerDataByContainerName($container_name)
    {
        $container = Container::where('container_name', $container_name)->first();

        if (!$container) {
            return response()->json(['error' => 'Container not found'], 404);
        }

        $container->load('arduino.rele.sensor.sensorType', 'values');

        return response()->json($container);
    }

    public function getContainerDataByContainerLocation($container_location)
    {
        $container = Container::where('container_location', $container_location)->first();

        if (!$container) {
            return response()->json(['error' => 'Container not found'], 404);
        }

        $container->load('arduino.rele.sensor.sensorType', 'values');

        return response()->json($container);
    }

    public function getContainerDataByArduinoName($arduino_name)
    {
        $arduino = Arduino::where('arduino_name', $arduino_name)->first();

        if (!$arduino) {
            return response()->json(['error' => 'Arduino not found'], 404);
        }

        $arduino->load('rele.sensor.sensorType', 'container.values');

        return response()->json($arduino);
    }

    public function getContainerDataBySensorName($sensor_name)
    {
        $sensor = Sensor::where('sensor_name', $sensor_name)->first();

        if (!$sensor) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        $sensor->load('sensorType', 'rele.arduino.container.values');

        return response()->json($sensor);
    }

    public function getContainerDataBySensorType($sensor_type)
    {
        $sensorType = SensorType::where('sensor_type_name', $sensor_type)->first();

        if (!$sensorType) {
            return response()->json(['error' => 'Sensor type not found'], 404);
        }

        $sensorType->load('sensors.rele.arduino.container.values');

        return response()->json($sensorType);
    }

    public function getContainerDataBySensorTypeName($sensor_type_name)
    {
        $sensorType = SensorType::where('sensor_type_name', $sensor_type_name)->first();

        if (!$sensorType) {
            return response()->json(['error' => 'Sensor type not found'], 404);
        }

        $sensorType->load('sensors.rele.arduino.container.values');

        return response()->json($sensorType);
    }

    

}
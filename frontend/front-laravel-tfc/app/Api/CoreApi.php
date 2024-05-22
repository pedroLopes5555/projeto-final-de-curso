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

    protected $base_url = 'https://hydrogrowthmanager.azurewebsites.net/';

    public function sendTest(){
        return $this->post('automation/SendTest', ['micrcocontrollerID' => 'AA:BB:CC', 'type' => 'TDS', 'value' => '102.2']);
    }

    
    public function RequestContainers(){
        return $this->post('automation/RequestContainers');
    }

    public function UpdateValue($sensor_id, $value){
        return $this->post('microcontroller/UpdateValue', ['sensor_id' => $sensor_id, 'value' => $value]);
    }

    public function GetDesiredValue(){
        return $this->post('microcontroller/GetDesiredValue');
    }

    public function setDesiredValue($container_id, $DesiredValue, $ReadingTypeEnum){
        return $this->post('automation/SetDesiredValue', ['ContainerId' => $container_id, 'DesiredValue' => $DesiredValue, 'ValueType' => $ReadingTypeEnum]);
    }

    public function RequestUserContainers($user_id){
        return $this->post('automation/RequestUserContainers', ['user_id' => $user_id]);
    }

    public function RequestContainerValues($container_id){
        return $this->post('automation/RequestContainerValues', ['container_id' => $container_id]);
    }

}
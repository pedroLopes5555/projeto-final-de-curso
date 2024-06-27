<?php


namespace App\Api;
use App\Api\BaseApi;

class ArduinoApi extends BaseApi{

    
    public function CreateMicrocontroller($user_guid, $arduino_guid, $arduino_name){
        return $this->post('automation/CreateMicrocontroller', ['user' => ['id' => $user_guid], 'microcontroller' => ['id' => $arduino_guid, 'name' => $arduino_name]]);
    }

    public function AddMicrocontrollerToContainer($arduino_guid, $container_guid){
        return $this->post('automation/AddMicrocontrollerToContainer', ['microcontrollerId' => $arduino_guid, 'containerId' => $container_guid]);
    }


}
<?php


namespace App\Api;
use App\Api\BaseApi;

class ContainerApi extends BaseApi{

    
    public function CreateNewContainer($container_name, $container_location, $user_guuid){
        return $this->post('automation/CreateNewContainer', ['name' => $container_name, 'location' => $container_location, 'UserId' => $user_guuid]);
    }

    public function SetContainerConfig($container_guid, $valueType, $value, $margin, $actionTime){
        return $this->post('automation/SetContainerConfig', ['ContainerId' => $container_guid, 'ValueType' => $valueType, 'DesiredValue' => $value, 'Margin' => $margin, 'ActionTime' => $actionTime]);
    }

    public function RequestUserContainer($user_guid){
        return $this->post('automation/RequestUserContainers', $user_guid);
    }

    public function RequestContainerValues($container_guid){
        return $this->post('automation/RequestContainerValues', $container_guid);
    }

    public function EditContainer($container_guid, $container_name, $container_location){
        return $this->post('automation/EditContainer', ['id' => $container_guid, 'name' => $container_name, 'location' => $container_location]);
    }

    public function DeleteContainer($container_guid){
        return $this->post('automation/DeleteContainer', $container_guid);
    }

    public function AddManualCommand($start, $finish, $container_guid, $operationType, $command){
        return $this->post('automation/AddManualCommand', ['start' => $start, 'finish' => $finish, 'containerId' => $container_guid, 'operationType' => $operationType, 'command' => $command]);

    }

}
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


class LoginApi extends BaseApi {
    

    public function login($username, $password){
        return $this->post('automation/UserLogin', ['userName' => $username, 'password' => $password]);
    }

    public function register($username, $password, $super, $perms){
        return $this->post('automation/RegistNewUser', ['userName' => $username, 'userPassword' => $password, 'super' => $super, 'permissions' => $perms]);
    }

    public function update($user_guid, $username, $perms){
        return $this->post('automation/UpdateUser', ['id' => $user_guid, 'userName' => $username, 'permissions' => $perms]);
    }

    public function delete($user_guid){
        return $this->post('automation/DeleteUser', $user_guid);
    }

}
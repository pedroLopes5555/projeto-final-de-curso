<?php

namespace App\Http\Controllers;

use App\Models\Perm;
use App\Models\User;
use App\Api\LoginApi;
use App\Models\Arduino;
use App\Models\Container;
use App\Models\TargetValue;
use App\Models\PermRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function perms(){
        $perm_areas = [];
        collect(\Route::getRoutes())->each(function($r) use (&$perm_areas){
            if(!isset($r->action['middleware'])) return;
            $m = $r->action['middleware'];
    
            collect($m)->each(function($m) use (&$perm_areas){
              if(preg_match('/^perms:([^_].+)$/', $m, $matches)){
                $perm_areas[] = $matches[1];
              }
            });
          });

        $perm_areas = collect($perm_areas)->unique()->sort()->toArray();
        if(count($perm_areas)){
            $perm_areas = array_combine(range(0, count($perm_areas)-1), array_values($perm_areas));
        }
        return view('admin.users.perms', ['perm_areas' => $perm_areas]);
    }

    public function create(Request $request){

        $user_super = false;
        $perms = 2;
        if(isset($request->user_super)){
            $user_super = true;
            $perms = 1;
        }


        $api = new LoginApi();
        $response = $api->register($request->user_name, $request->user_pass, $user_super, $perms);

        if(!$response['userCreated']){
            return response()->json(['status' => 'error', 'message' => 'Error creating user']);
        }

        $perm = Perm::where('perm_name', 'autorized')->first();

        $user = new User();
        $user->user_name = $request->user_name;
        $user->user_pass = Hash::make($request->user_pass);
        $user->user_super = $user_super;
        $user->perm_id = $perm->perm_id;
        $user->user_guid = $response['id'];

        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User created successfully']);
    }

    public function update(Request $request){

        $user = User::find($request->user_id);

        $user_super = false;
        $perms = 2;
        if(isset($request->user_super)){
            $user_super = true;
            $perms = 1;
        }

        $api = new LoginApi();
        $response = $api->update($user->user_guid, $request->user_name, $perms);

        if(!$response){
            return response()->json(['status' => 'error', 'message' => 'Error updating user']);
        }

        $user->user_name = $request->user_name;
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
    }

    public function delete(Request $request){

        $user = User::find($request->user_id);
        // api call to delete user on the backend
        // $api = new LoginApi();
        // $response = $api->delete($user->user_guid);

        // if(!$response){
        //     return response()->json(['status' => 'error', 'message' => 'Error deleting user']);
        // }

        $arduino = Arduino::where('user_id', $user->user_id)->get();
        foreach($arduino as $a){
            $a->delete();
        }
        $container = Container::where('user_id', $user->user_id)->get();
        foreach($container as $c){

            $targetValue = TargetValue::where('container_id', $c->container_id)->first();
            if($targetValue){
                $targetValue->delete();
            }

            $c->delete();
        }
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
    }

    public function newPerm(Request $request){
        $perm = new Perm();
        $perm->perm_name = $request->perm_name;
      
        $perm->save();

        $perm_id = $perm->perm_id;
        // get perm_names[] 
        $perm_names = $request->perm_names;
        // save all the perm_names
        foreach($perm_names as $perm_name){
            $perm_relation = new PermRelation();
            $perm_relation->perm_id = $perm_id;
            $perm_relation->perm_name = $perm_name;
            $perm_relation->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Perm created successfully']);
    }

    public function updatePerm(Request $request){
        $perm = Perm::find($request->perm_id);
        $perm->perm_name = $request->perm_name;
        $perm->save();

        $perm_id = $perm->perm_id;
        // get perm_names[]
        $perm_names = $request->perm_names;
        // delete all perm_names with perm_id
        PermRelation::where('perm_id', $perm_id)->delete();
        // save all the perm_names
        foreach($perm_names as $perm_name){
            $perm_relation = new PermRelation();
            $perm_relation->perm_id = $perm_id;
            $perm_relation->perm_name = $perm_name;
            $perm_relation->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Perm updated successfully']);
    }

    public function deletePerm(Request $request){
        $perm = Perm::find($request->perm_id);
        $perm->delete();
        return response()->json(['status' => 'success', 'message' => 'Perm deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Perm;
use App\Models\User;
use App\Models\PermRelation;
use Illuminate\Http\Request;

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
      dd($request->all());
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User created successfully']);
    }

    public function update(Request $request){
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
    }

    public function delete(Request $request){
        $user = User::find($request->id);
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
        return response()->json(['status' => 'success', 'message' => 'Perm updated successfully']);
    }

    public function deletePerm(Request $request){
        $perm = Perm::find($request->perm_id);
        $perm->delete();
        return response()->json(['status' => 'success', 'message' => 'Perm deleted successfully']);
    }
}

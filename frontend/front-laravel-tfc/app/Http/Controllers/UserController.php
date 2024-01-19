<?php

namespace App\Http\Controllers;

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
}

<?php
namespace App\Http\Controllers;


use App\Models\Perm;
use App\Models\User;

use App\Models\Arduino;
use Illuminate\Http\Request;

class SelectController_api extends Controller{
  use \App\Traits\ApiUtils;

  private function search($query, $name, $request){
    $page = $request->page - 1 ?? 0;

    $query = $query->where($name, 'LIKE', '%'.$request->q.'%');

    $count = (clone $query)->count();
    $query = $query->skip($page * self::PAGING)
    ->take(self::PAGING)
    ->get();
    return $this->apiResponseSelect($query, $count, self::PAGING);
  }

  const PAGING = 10;

  public function arduinos(Request $request) {
    $query = Arduino::select('arduino_id as id', 'arduino_name as text');
    return $this->search($query, 'arduino_name', $request);
  }

  public function perms(Request $request) {
    $query = Perm::select('perm_id as id', 'perm_name as text');
    return $this->search($query, 'perm_name', $request);
  }

  public function containers(Request $request){
    $user = User::getCurrent();

    $query = $user->containers()->select('container_id as id', 'container_name as text');
    return $this->search($query, 'container_name', $request); 
  }

}

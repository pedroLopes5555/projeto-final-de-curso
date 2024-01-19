<?php
namespace App\Traits;

trait ApiUtils{

  function rawApiResponse($data="Success"){
    return response(json_encode($data), 200)
      ->header('Content-Type', 'application/json');
  }
  function apiResponse($data="Success", $error=0){
    $data = [
      'data' => $data,
      'error' => $error
    ];
    return $this->rawApiResponse($data);
  }

  function apiResponseSelect($results, $total, $paging){
    $data = compact('results', 'total', 'paging');
    return $this->rawApiResponse($data);
  }

  function apiError($data="Error", $error=1){
    $data = [
      'data' => $data,
      'error' => $error
    ];
    return $this->rawApiResponse($data);
  }

}

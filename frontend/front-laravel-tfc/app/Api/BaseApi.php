<?php
namespace App\Api;

use GuzzleHttp\RequestOptions;
use Http;
class BaseApi{
    protected $base_url = 'https://hydrogrowthmanager.azurewebsites.net/';

    protected function get($url, $data = []){
        return- $this->call('GET', $this->base_url . $url, $data);
    }

    protected function post($url, $data = []){
        return $this->call('POST', $this->base_url . $url, $data);
    }

    private function call($method, $url, $data = [], $raw = false){
        if($method=='GET'){
            $params = [
                RequestOptions::QUERY => $data
            ];
        }else if(!$raw){
            $params = [
                RequestOptions::JSON => $data
            ];
        }else{
            $params = [
                RequestOptions::BODY => $data
            ];
        }
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->send($method, $url, $params);
        $status = $response->getStatusCode();
        $statusi = floor($status / 100);
        if($statusi != 2){
            switch($status){
                case 400:
                    throw new \Exception('Bad Request', $status);
                    break;
                    
                case 401:
                case 403:
                    throw new \Exception('Unauthorized Key', $status);
                    break;
                
                case 404:
                    throw new \Exception('Not Found', $status);
                    break;
            
                case 409:
                    throw new \Exception('Conflict', $status);
                    break;
                    
                case 410:
                    throw new \Exception('Gone', $status);
                    break;
                    
                case 500:
                    throw new \Exception('Server Error', $status);
                    break;
            }
            throw new \Exception($status);
        }
        return $response->json();
    }

}
<?php

namespace App\Console\Commands;

use App\Api\CoreApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MakeApiCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an API call every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $data = [
            "micrcocontrollerID"=>"AA:BB:CC",
            "type"=>"TDS",
            "value"=>"102.2"
        ];

        // Core api

        $response = Http::post('https://hydrogrowthmanager.azurewebsites.net/automation/sendTest', $data);

        $this->info('Making API call...');
        //  php artisan make:api-call
        // * * * * * cd /path-to-your-laravel-app && php artisan schedule:run >> /dev/null 2>&1

        if($response->successful()){
            $this->info('Success', $response->body());
        }else{
             $this->error('Error', $response->body());
        }


    }
}

<?php

namespace App\Http\Controllers;

use App\Api\ContainerApi;
use App\Models\Arduino;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        // Get current user
        $user = User::getCurrent();

        // Count active and inactive Arduinos
        $arduinos = Arduino::where('user_id', $user->user_id)->get();
        $arduinosActive = 0;
        $arduinosInactive = 0;

        foreach ($arduinos as $arduino) {
            if ($arduino->container_id) {
                $arduinosActive++;
            } else {
                $arduinosInactive++;
            }
        }

        // Initialize arrays for containers and their readings
        $containers = [];
        $typeDict = [
            1 => 'ph',
            2 => 'ec',
            4 => 'temperature'
        ];

        // Simulate API response for containers (replace with actual API call)
        $api = new ContainerApi();
        $response = $api->RequestUserContainer($user->user_guid);

        // Process each container and its readings
        foreach ($response as $container) {
            $containerId = $container['id'];
            $containerReadings = $api->RequestContainerValues($containerId);
            $containers[$containerId] = [
                'name' => $container['name'],
                'ph' => [],
                'ec' => [],
                'temperature' => []
            ];

            foreach ($containerReadings as $reading) {
                $readingType = $reading['readingType'];
                $readingValue = $reading['reading'];
                $time = $reading['time'];

                // Accumulate readings for each type
                $containers[$containerId][$typeDict[$readingType]][] = [
                    'reading' => $readingValue,
                    'time' => $time
                ];
            }
        }

        // Pass data to the view, including $typeDict
        return view('admin.index', [
            'arduinosActive' => $arduinosActive,
            'arduinosInactive' => $arduinosInactive,
            'containers' => $containers,
            'typeDict' => $typeDict, // Pass $typeDict to the view
        ]);
    }
}

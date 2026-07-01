<?php

namespace App\Services\Api\IoT;

use App\Models\IoT;
use App\Foundations\Service;

class DeviceService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): mixed
    {
        $devices = IoT::select('id', 'distance', 'ph', 'oxygen_concentration', 'oxygen_saturation', 'temperature', 'created_at')
            ->latest()
            ->limit(10)
            ->get();

        return $devices;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        $device = IoT::create([
            'distance'             => $request['distance'],
            'ph'                   => $request['ph'],
            'oxygen_concentration' => $request['oxygen_concentration'],
            'oxygen_saturation'    => $request['oxygen_saturation'],
            'temperature'          => $request['temperature'],
        ]);

        return $device ? true : false;
    }
}

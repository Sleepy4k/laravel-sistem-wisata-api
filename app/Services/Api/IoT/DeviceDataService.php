<?php

namespace App\Services\Api\IoT;

use App\Foundations\Service;
use App\Models\IoT;

class DeviceDataService extends Service
{
    public function distance(IoT $iot): mixed
    {
        return $iot->distance;
    }

    public function temperature(IoT $iot): mixed
    {
        return $iot->temperature;
    }

    public function oxygenConcentration(IoT $iot): mixed
    {
        return $iot->oxygen_concentration;
    }

    public function oxygenSaturation(IoT $iot): mixed
    {
        return $iot->oxygen_saturation;
    }
}

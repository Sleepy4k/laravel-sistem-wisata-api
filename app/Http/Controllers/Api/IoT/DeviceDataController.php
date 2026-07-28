<?php

namespace App\Http\Controllers\Api\IoT;

use App\Foundations\Controller;
use App\Models\IoT;
use App\Policies\DeviceDataPolicy;
use App\Services\Api\IoT\DeviceDataService;
use App\Traits\Authorizable;

class DeviceDataController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DeviceDataService $service,
        private $policy = DeviceDataPolicy::class,
        private $abilities = [
            'getDistance' => 'viewAny',
            'getTemperature' => 'viewAny',
            'getOxygenConcentration' => 'viewAny',
            'getOxygenSaturation' => 'viewAny',
        ]
    ) {}

    public function getDistance(IoT $iot)
    {
        return $this->service->distance($iot);
    }

    public function getTemperature(IoT $iot)
    {
        return $this->service->temperature($iot);
    }

    public function getOxygenConcentration(IoT $iot)
    {
        return $this->service->oxygenConcentration($iot);
    }

    public function getOxygenSaturation(IoT $iot)
    {
        return $this->service->oxygenSaturation($iot);
    }
}

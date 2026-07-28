<?php

namespace App\Http\Controllers\Api\IoT;

use App\Facades\ApiResponse;
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
        return ApiResponse::success($this->service->distance($iot), 'Successfully retrieved device distance data.', 200);
    }

    public function getTemperature(IoT $iot)
    {
        return ApiResponse::success($this->service->temperature($iot), 'Successfully retrieved device temperature data.', 200);
    }

    public function getOxygenConcentration(IoT $iot)
    {
        return ApiResponse::success($this->service->oxygenConcentration($iot), 'Successfully retrieved device oxygen concentration data.', 200);
    }

    public function getOxygenSaturation(IoT $iot)
    {
        return ApiResponse::success($this->service->oxygenSaturation($iot), 'Successfully retrieved device oxygen saturation data.', 200);
    }
}

<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Api\ApiResponseManager;

/**
 * @method static \Illuminate\Http\JsonResponse success(mixed $data = null, string $message = 'Success', int $code = 200)
 * @method static \Illuminate\Http\JsonResponse error(string $message = 'Error', int $code = 400, mixed $errors = null)
 * @method static \Illuminate\Http\JsonResponse paginated(\Illuminate\Pagination\LengthAwarePaginator $paginator, string $message = 'Success')
 *
 * @see \Modules\Api\ApiResponseManager
 *
 * @mixins \Modules\Api\ApiResponseManager
 */
class ApiResponse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ApiResponseManager::class;
    }
}

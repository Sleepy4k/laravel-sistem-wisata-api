<?php

namespace Modules\Api;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseManager
{
    /**
     * Return a successful response with data
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(mixed $data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $this->getMeta()
        ], $statusCode);
    }

    /**
     * Return an error response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = 'Error', int $statusCode = 400, mixed $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => $this->getMeta()
        ], $statusCode);
    }

    /**
     * Return paginated response
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginated(LengthAwarePaginator $paginator, string $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'meta' => $this->getMeta()
        ]);
    }

    public function custom(mixed $data, int $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Get meta information
     *
     * @return array
     */
    private function getMeta()
    {
        return [
            'timestamp' => date('d-m-Y H:i:s'),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
        ];
    }
}

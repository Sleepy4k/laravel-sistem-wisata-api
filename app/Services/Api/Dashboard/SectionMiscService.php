<?php

namespace App\Services\Api\Dashboard;

use App\Foundations\Service;
use App\Http\Resources\Dashboard\BusinessFieldResource;

class SectionMiscService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function columns(string $role, $business): mixed
    {
        $business->load('fields');

        $fields = $business->fields->map(function ($field) {
            return [
                'data' => $field->name,
                'name' => $field->name,
                'title' => $field->label,
                'orderable' => true,
                'searchable' => true,
            ];
        });

        $defaultColumns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'orderable' => true,
                'searchable' => true,
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => 'Created At',
                'orderable' => true,
                'searchable' => false,
            ],
            [
                'data' => 'updated_at',
                'name' => 'updated_at',
                'title' => 'Updated At',
                'orderable' => true,
                'searchable' => false,
            ],
        ];

        return array_merge($defaultColumns, $fields->toArray());
    }

    /**
     * Display a listing of the resource.
     */
    public function fields(string $role, $business): mixed
    {
        $business->load('fields');

        return BusinessFieldResource::collection($business->fields);
    }
}

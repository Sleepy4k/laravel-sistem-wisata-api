<?php

namespace App\Services\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Dashboard\SidebarResource;
use App\Models\Sidebar;
use Illuminate\Http\Request;

class SidebarService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(Request $request): mixed
    {
        $sidebars = Sidebar::select('name', 'order', 'is_spacer', 'sidebar_meta_id')
            ->with('meta:id,route,parameters,permissions,icon')
            ->orderBy('order', 'asc')
            ->get();

        return ApiResponse::success(SidebarResource::collection($sidebars), 'Successfully retrieved sidebar data.', 200);
    }
}

<?php

namespace App\Services\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Models\Business;
use App\Models\Role;
use App\Models\Sidebar;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SummaryService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(): JsonResponse
    {
        $user = auth('api')->user();
        $role = $user->getRoleNames()[0];
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        $response = [];

        if ($role === config('rbac.role.highest')) {
            $response['roles'] = Role::withCount('users')
                ->get()
                ->pluck('users_count', 'name')
                ->toArray();
        }

        $response['menus'] = Sidebar::select('name', 'sidebar_meta_id')
            ->whereHas('meta', function ($query) use ($permissions) {
                $query->where(function ($q) use ($permissions) {
                    foreach ($permissions as $permission) {
                        $q->orWhereJsonContains('permissions', $permission);
                    }
                });
            })
            ->with('meta:id,permissions')
            ->get()
            ->map(function ($menu) {
                $permission = $menu->meta->permissions[0] ?? null;
                return [
                    'name' => $menu->name,
                    'prefix' => $permission ? explode('.', $permission)[0] : null,
                ];
            })
            ->groupBy('prefix');

        $response['summary'] = [];
        $activeRoles = array_keys(config('rbac.list.dynamic_permissions'));

        if ($role === config('rbac.role.default')) {
            $activeRoles = array_filter($activeRoles, fn($r) => $r !== 'bumdes');
        }

        foreach ($activeRoles as $roleName) {
            $menuItems = $response['menus']->get($roleName, []);

            if ($menuItems->isEmpty()) {
                continue;
            }

            $businessIds = Business::whereIn('slug', $menuItems->pluck('name')->map(fn($name) => Str::slug($name)))
                ->pluck('id')
                ->toArray();

            $income = Transaction::whereIn('business_id', $businessIds)->where('type', 'income')
                ->withSum('detail as total_income', 'amount')
                ->get()
                ->sum('total_income');

            $outcome = Transaction::whereIn('business_id', $businessIds)->where('type', 'outcome')
                ->withSum('detail as total_outcome', 'amount')
                ->get()
                ->sum('total_outcome');

            $response['summary'][$roleName] = [
                'total_income' => $income,
                'total_outcome' => $outcome,
            ];
        }

        return ApiResponse::success($response, 'Successfully retrieved dashboard summary.', 200);
    }
}

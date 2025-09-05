<?php

namespace App\Models;

use App\Concerns\HasUlid;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasUlid;

    /**
     * Check if the token has the given ability.
     *
     * @param string $ability
     * @return bool
     */
    public function can($ability): bool
    {
        if (!$this->tokenable->can($ability)) {
            return false;
        }

        $abilities = collect($this->abilities)->filter(function($ability) {
            return $ability !== '*';
        })->toArray();

        if (count($abilities) > 0) {
            return $this->canDb($abilities);
        }

        return true;
    }

    /**
     * Check if the token has the given ability in the database.
     *
     * @param array<string|int, mixed> $ability
     * @return bool
     */
    protected function canDb(array $ability): bool
    {
        return array_key_exists($ability, array_flip($this->abilities));
    }
}

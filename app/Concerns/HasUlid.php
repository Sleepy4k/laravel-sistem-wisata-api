<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait HasUlid
{
    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the data type of the primary key ID.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * Generate a new ULID for the model.
     *
     * @param string|null $keyname
     *
     * @return string
     */
    public static function generateUlid(?string $keyname): string
    {
        $ulid = null;

        do {
            $ulid = Str::ulid();
        } while (static::where($keyname ?? 'id', $ulid)->exists());

        return $ulid;
    }

    /**
     * Boot the ULID trait for the model.
     *
     * @return void
     */
    protected static function bootHasUlid()
    {
        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = static::generateUlid($model->getKeyName());
            }
        });
    }
}

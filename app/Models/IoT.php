<?php

namespace App\Models;

use App\Concerns\Cacheable;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IoT extends Model
{
    use HasFactory, HasUuid, Loggable, Cacheable;

    public $table = 'iot';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'distance',
        'ph',
        'oxygen_concentration',
        'oxygen_saturation',
        'temperature',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'distance' => 'float',
            'ph' => 'float',
            'oxygen_concentration' => 'float',
            'oxygen_saturation' => 'float',
            'temperature' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'iot.cache';
    }
}

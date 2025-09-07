<?php

namespace App\Models;

use App\Concerns\Cacheable;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarMeta extends Model
{
    use HasFactory, HasUuid, Loggable, Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'icon',
        'route',
        'permissions',
        'parameters',
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
            'icon' => 'string',
            'route' => 'string',
            'permissions' => 'array',
            'parameters' => 'array',
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
        return 'sidebar.meta.cache';
    }

    /**
     * Get the sidebar items associated with the meta.
     */
    public function sidebars()
    {
        return $this->hasMany(Sidebar::class, 'sidebar_meta_id', 'id');
    }
}

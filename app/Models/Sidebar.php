<?php

namespace App\Models;

use App\Concerns\Cacheable;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    use HasFactory, HasUuid, Loggable, Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'order',
        'is_spacer',
        'sidebar_meta_id',
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
            'name' => 'string',
            'order' => 'integer',
            'is_spacer' => 'boolean',
            'sidebar_meta_id' => 'string',
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
        return 'sidebar.cache';
    }

    /**
     * Get the meta associated with the sidebar.
     */
    public function meta()
    {
        return $this->belongsTo(SidebarMeta::class, 'sidebar_meta_id', 'id');
    }

    /**
     * Get the full path of the sidebar.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        $path = $this->name;

        if ($this->parent) {
            $path = $this->parent->getFullPathAttribute() . ' > ' . $path;
        }

        return $path;
    }

    /**
     * Get the sidebar's meta data.
     *
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->meta ? $this->meta->toArray() : [];
    }
}

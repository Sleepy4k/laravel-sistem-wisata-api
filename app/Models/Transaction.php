<?php

namespace App\Models;

use App\Concerns\Cacheable;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuid, Loggable, Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'business_id',
        'transaction_date',
        'type',
        'user_id',
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
            'business_id' => 'string',
            'transaction_date' => 'date:Y-m-d',
            'type' => 'string',
            'user_id' => 'string',
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
        return 'transaction.cache';
    }

    /**
     * Get the business that owns the transaction.
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the detail for the transaction.
     */
    public function detail()
    {
        return $this->hasOne(TransactionDetail::class, 'transaction_id', 'id');
    }
}

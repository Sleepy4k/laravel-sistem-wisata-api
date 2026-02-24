<?php

namespace App\Models;

use App\Concerns\Cacheable;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use Illuminate\Database\Eloquent\Model;

class BusinessFormula extends Model
{
    use HasUuid, Loggable, Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'business_id',
        'result',
        'result_label',
        'field_a',
        'operator',
        'field_b',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'          => 'string',
            'business_id' => 'string',
            'result'      => 'string',
            'result_label'=> 'string',
            'field_a'     => 'string',
            'operator'    => 'string',
            'field_b'     => 'string',
            'order'       => 'integer',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    /**
     * Set the cache prefix.
     */
    public function setCachePrefix(): string
    {
        return 'business.formula.cache';
    }

    /**
     * Get the owning business.
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Evaluate the formula against a row of data.
     *
     * @param  array<string, mixed>  $data  Keyed by field name / literal value
     * @return float|null
     */
    /**
     * Evaluate the formula against a row of field data.
     *
     * Expression format: [{ column, operator }, ...]
     * The first step has operator = null (starting value).
     * Each subsequent step applies its operator between the accumulated
     * result and the value of `column` from $data.
     *
     * @param  array<string, mixed>  $data  Keyed by field name
     * @return float|null
     */
    public function evaluate(array $data): ?float
    {
        $steps = $this->expression ?? [];

        if (empty($steps)) {
            return null;
        }

        $result = null;

        foreach ($steps as $step) {
            $column   = $step['column']   ?? null;
            $operator = $step['operator'] ?? null;

            $operand = isset($data[$column]) ? (float) $data[$column] : 0.0;

            if ($result === null) {
                // First step — no operator, just seed the result
                $result = $operand;
                continue;
            }

            $result = match ($operator) {
                '+'     => $result + $operand,
                '-'     => $result - $operand,
                '*'     => $result * $operand,
                '/'     => $operand != 0 ? $result / $operand : null,
                '%'     => $operand != 0 ? fmod($result, $operand) : null,
                '^'     => $result ** $operand,
                default => $result,
            };
        }

        return $result;
    }
}

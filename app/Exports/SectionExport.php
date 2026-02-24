<?php

namespace App\Exports;

use App\Enums\TransactionType;
use App\Models\Business;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SectionExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    /**
     * Create a new export instance.
     */
    public function __construct(
        private Business $business,
        private string $role,
    ) {}

    /**
     * Build the base filtered query (replicates SectionService filter logic without pagination).
     */
    public function query()
    {
        $query = $this->business->transactions()->with('detail', 'user');

        $datFrom = request()->input('date_from', null);
        $dateTo = request()->input('date_to', null);
        $type = request()->input('filter_type', null);
        $searchInput = request()->input('search', []);
        $search = is_array($searchInput) ? ($searchInput['value'] ?? null) : $searchInput;

        if ($datFrom) {
            $query->whereDate('transaction_date', '>=', $datFrom);
        }

        if ($dateTo) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        $filters = request()->except([
            'date_from', 'date_to', 'filter_type', 'search',
            'length', 'start', 'order', 'columns', 'draw',
        ]);

        foreach ($filters as $key => $value) {
            if (!$value) {
                continue;
            }

            $query->whereHas('detail', function ($q) use ($key, $value) {
                if (is_array($value)) {
                    $q->whereJsonContains("detail->{$key}", $value);
                } else {
                    $q->whereJsonPath("detail->{$key}", '=', $value);
                }
            });
        }

        $loweredType = $type ? strtolower($type) : null;
        if ($type && in_array($loweredType, [TransactionType::INCOME->value, TransactionType::EXPENSE->value])) {
            $query->where('type', $loweredType);
        } elseif ($type && TransactionType::fromLabel($loweredType)) {
            $query->where('type', TransactionType::fromLabel($loweredType)->value);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('detail', function ($dq) use ($search) {
                    $dq->where('note', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%")
                        ->orWhereRaw("detail LIKE ?", ["%{$search}%"]);
                })->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        return $query->orderBy('transaction_date', 'asc');
    }

    /**
     * Map each row for the spreadsheet.
     */
    public function map($transaction): array
    {
        $this->business->loadMissing('fields');

        $detail = $transaction->detail ? (is_array($transaction->detail->detail) ? $transaction->detail->detail : json_decode($transaction->detail->detail ?? '{}', true)) : [];
        $detail = $detail ?? [];

        $row = [
            $transaction->id,
            TransactionType::fromCase($transaction->type) ?? $transaction->type,
            $transaction->transaction_date?->format('d-m-Y'),
            $transaction->detail?->amount ? 'Rp.' . number_format((float) $transaction->detail->amount, 2, ',', '.') : '-',
            $transaction->detail?->note ?? '-',
        ];

        foreach ($this->business->fields as $field) {
            $row[] = $detail[$field->name] ?? '-';
        }

        $row[] = $transaction->created_at?->format('d-m-Y H:i:s');
        $row[] = $transaction->updated_at?->format('d-m-Y H:i:s');

        return $row;
    }

    /**
     * Return column headings (dynamic based on business fields).
     */
    public function headings(): array
    {
        $this->business->loadMissing('fields');

        $base = ['ID', 'Tipe', 'Tanggal Transaksi', 'Jumlah', 'Catatan'];

        $dynamic = $this->business->fields->map(fn($f) => $f->label)->toArray();

        return array_merge($base, $dynamic, ['Dibuat Pada', 'Diperbarui Pada']);
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return $this->business->name;
    }

    /**
     * Style the header row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'],
                ],
            ],
        ];
    }
}

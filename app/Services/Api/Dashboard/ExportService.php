<?php

namespace App\Services\Api\Dashboard;

use App\Enums\TransactionType;
use App\Exports\SectionExport;
use App\Foundations\Service;
use App\Models\Business;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maatwebsite\Excel\Facades\Excel;

class ExportService extends Service
{
    /**
     * Build the filtered, unpaginated transaction query for a business.
     */
    protected function buildQuery(Business $business): Builder|HasMany
    {
        $query = $business->transactions()->with('detail', 'user');

        $dateFrom = request()->input('date_from', null);
        $dateTo = request()->input('date_to', null);
        $type = request()->input('filter_type', null);
        $searchInput = request()->input('search', []);
        $search = is_array($searchInput) ? ($searchInput['value'] ?? null) : $searchInput;

        if ($dateFrom) {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        $filters = request()->except([
            'date_from',
            'date_to',
            'filter_type',
            'search',
            'length',
            'start',
            'order',
            'columns',
            'draw',
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
     * Export data as an Excel (.xlsx) file download.
     */
    public function excel(string $role, Business $business): mixed
    {
        $filename = sprintf('%s-%s-export.xlsx', $role, $business->slug);

        return Excel::download(new SectionExport($business, $role), $filename);
    }

    /**
     * Export data as a PDF file download.
     */
    public function pdf(string $role, Business $business): mixed
    {
        $business->loadMissing('fields');
        $transactions = $this->buildQuery($business)->get();

        $pdf = Pdf::loadView('exports.section.pdf', [
            'business' => $business,
            'role'     => $role,
            'transactions' => $transactions,
        ])->setPaper('a4', 'landscape');

        $filename = sprintf('%s-%s-export.pdf', $role, $business->slug);

        return $pdf->download($filename);
    }

    /**
     * Return data formatted for client-side printing.
     */
    public function print(string $role, Business $business): mixed
    {
        $business->loadMissing('fields');
        $transactions = $this->buildQuery($business)->get();

        return view('exports.section.print', [
            'business'     => $business,
            'role'         => $role,
            'transactions' => $transactions,
        ]);
    }
}

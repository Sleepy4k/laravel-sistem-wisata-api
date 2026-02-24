<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print - {{ $business->name }}</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                color: #1a1a1a;
                padding: 20px;
            }

            .header {
                text-align: center;
                margin-bottom: 18px;
                padding-bottom: 12px;
                border-bottom: 2px solid #000;
            }

            .header h1 {
                font-size: 18px;
                font-weight: bold;
            }

            .header h2 {
                font-size: 13px;
                font-weight: normal;
                color: #444;
                margin-top: 4px;
            }

            .meta {
                display: flex;
                gap: 24px;
                margin-bottom: 14px;
                font-size: 10px;
                color: #555;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 8px;
            }

            thead tr {
                background-color: #333;
                color: #fff;
            }

            thead th {
                padding: 7px 9px;
                text-align: left;
                font-size: 10px;
                white-space: nowrap;
            }

            tbody tr {
                border-bottom: 1px solid #ddd;
            }

            tbody tr:nth-child(even) {
                background-color: #f7f7f7;
            }

            tbody td {
                padding: 6px 9px;
                font-size: 10px;
                vertical-align: top;
            }

            .badge {
                padding: 2px 7px;
                border-radius: 10px;
                font-size: 9px;
                font-weight: bold;
            }

            .badge-income {
                background-color: #d4edda;
                color: #155724;
            }

            .badge-outcome {
                background-color: #f8d7da;
                color: #721c24;
            }

            .footer {
                margin-top: 20px;
                font-size: 9px;
                color: #888;
                display: flex;
                justify-content: space-between;
            }

            .summary {
                margin-top: 16px;
                padding: 10px 14px;
                background: #f5f5f5;
                border: 1px solid #ddd;
                font-size: 10px;
            }

            .summary b {
                font-size: 11px;
            }

            @media print {
                body {
                    padding: 10px;
                }

                .no-print {
                    display: none;
                }

                table {
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Sistem Wisata</h1>
            <h2>Laporan Data &mdash; {{ $business->name }} ({{ strtoupper($role) }})</h2>
        </div>

        <div class="meta">
            @if (request()->input('date_from') || request()->input('date_to'))
                <span><strong>Periode:</strong>
                    {{ request()->input('date_from') ? \Carbon\Carbon::parse(request()->input('date_from'))->format('d-m-Y') : '...' }}
                    s/d
                    {{ request()->input('date_to') ? \Carbon\Carbon::parse(request()->input('date_to'))->format('d-m-Y') : '...' }}
                </span>
            @endif
            @if (request()->input('filter_type'))
                <span><strong>Tipe:</strong> {{ ucfirst(request()->input('filter_type')) }}</span>
            @endif
            <span><strong>Total Data:</strong> {{ $transactions->count() }}</span>
            <span><strong>Dicetak:</strong> {{ now()->format('d-m-Y H:i:s') }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    @foreach ($business->fields as $field)
                        <th>{{ $field->label }}</th>
                    @endforeach
                    <th>Dibuat Pada</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalIncome = 0;
                    $totalExpense = 0;
                @endphp
                @forelse($transactions as $i => $transaction)
                    @php
                        $detail = $transaction->detail;
                        $extraDetail = $detail
                            ? (is_array($detail->detail)
                                ? $detail->detail
                                : json_decode($detail->detail ?? '{}', true))
                            : [];
                        $extraDetail = $extraDetail ?? [];
                        $isIncome = $transaction->type === 'income';
                        $amount = (float) ($detail?->amount ?? 0);
                        if ($isIncome) {
                            $totalIncome += $amount;
                        } else {
                            $totalExpense += $amount;
                        }
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <span class="badge {{ $isIncome ? 'badge-income' : 'badge-outcome' }}">
                                {{ \App\Enums\TransactionType::fromCase($transaction->type) ?? $transaction->type }}
                            </span>
                        </td>
                        <td>{{ $transaction->transaction_date?->format('d-m-Y') }}</td>
                        <td>{{ $detail ? 'Rp.' . number_format($amount, 2, ',', '.') : '-' }}</td>
                        <td>{{ $detail?->note ?? '-' }}</td>
                        @foreach ($business->fields as $field)
                            <td>{{ $extraDetail[$field->name] ?? '-' }}</td>
                        @endforeach
                        <td>{{ $transaction->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 6 + $business->fields->count() }}"
                            style="text-align:center;padding:20px;color:#888;font-style:italic;">
                            Tidak ada data untuk ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($transactions->isNotEmpty())
            <div class="summary">
                <b>Ringkasan</b>&nbsp;&nbsp;
                Total Pemasukan: <strong>Rp.{{ number_format($totalIncome, 2, ',', '.') }}</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Total Pengeluaran: <strong>Rp.{{ number_format($totalExpense, 2, ',', '.') }}</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Saldo: <strong>Rp.{{ number_format($totalIncome - $totalExpense, 2, ',', '.') }}</strong>
            </div>
        @endif

        <div class="footer">
            <span>{{ config('app.name') }} &mdash; Dokumen dibuat otomatis oleh sistem</span>
            <span>{{ now()->format('d-m-Y H:i:s') }}</span>
        </div>

        <div class="no-print" style="margin-top:20px;text-align:center;">
            <button onclick="window.print()"
                style="padding:8px 24px;background:#333;color:#fff;border:none;cursor:pointer;font-size:13px;border-radius:4px;">
                Cetak Dokumen
            </button>
        </div>
    </body>
</html>

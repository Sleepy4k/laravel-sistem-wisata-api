<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Export PDF - {{ $business->name }}</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 10px;
                color: #1a1a1a;
            }

            .header {
                text-align: center;
                margin-bottom: 16px;
                border-bottom: 2px solid #3b4fcf;
                padding-bottom: 10px;
            }

            .header h1 {
                font-size: 16px;
                color: #3b4fcf;
                font-weight: bold;
            }

            .header p {
                font-size: 10px;
                color: #555;
                margin-top: 4px;
            }

            .meta {
                margin-bottom: 12px;
                font-size: 9px;
                color: #666;
            }

            .meta span {
                margin-right: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead tr {
                background-color: #3b4fcf;
                color: #fff;
            }

            thead th {
                padding: 6px 8px;
                text-align: left;
                font-size: 9px;
                font-weight: bold;
                white-space: nowrap;
            }

            tbody tr:nth-child(even) {
                background-color: #f0f3ff;
            }

            tbody td {
                padding: 5px 8px;
                font-size: 9px;
                vertical-align: top;
                border-bottom: 1px solid #e0e0e0;
            }

            .badge {
                display: inline-block;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 8px;
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
                margin-top: 14px;
                font-size: 8px;
                color: #888;
                text-align: right;
            }

            .no-data {
                text-align: center;
                padding: 20px;
                color: #888;
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Laporan Data - {{ $business->name }}</h1>
            <p>Role: {{ strtoupper($role) }} &nbsp;|&nbsp; Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>

        <div class="meta">
            @if (request()->input('date_from') || request()->input('date_to'))
                <span>Periode:
                    {{ request()->input('date_from') ? \Carbon\Carbon::parse(request()->input('date_from'))->format('d-m-Y') : '...' }}
                    s/d
                    {{ request()->input('date_to') ? \Carbon\Carbon::parse(request()->input('date_to'))->format('d-m-Y') : '...' }}
                </span>
            @endif
            @if (request()->input('filter_type'))
                <span>Tipe: {{ ucfirst(request()->input('filter_type')) }}</span>
            @endif
            <span>Total Data: {{ $transactions->count() }}</span>
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
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <span class="badge {{ $isIncome ? 'badge-income' : 'badge-outcome' }}">
                                {{ \App\Enums\TransactionType::fromCase($transaction->type) ?? $transaction->type }}
                            </span>
                        </td>
                        <td>{{ $transaction->transaction_date?->format('d-m-Y') }}</td>
                        <td>{{ $detail ? 'Rp.' . number_format((float) $detail->amount, 2, ',', '.') : '-' }}</td>
                        <td>{{ $detail?->note ?? '-' }}</td>
                        @foreach ($business->fields as $field)
                            <td>{{ $extraDetail[$field->name] ?? '-' }}</td>
                        @endforeach
                        <td>{{ $transaction->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-data" colspan="{{ 6 + $business->fields->count() }}">Tidak ada data untuk ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dokumen ini dibuat secara otomatis oleh sistem &mdash; {{ config('app.name') }}
        </div>
    </body>
</html>

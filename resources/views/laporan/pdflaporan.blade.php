<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Prediksi Kelulusan</title>
    <style>
        @page {
            margin: 2cm;
            size: A4 portrait;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .header h2 {
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 5px 0;
            color: #7f8c8d;
        }

        .header .subtitle {
            font-size: 12px;
            color: #95a5a6;
            margin: 0;
        }

        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }

        .info-value {
            color: #6c757d;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #495057;
        }

        td {
            padding: 6px 4px;
            border: 1px solid #dee2e6;
            text-align: left;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            color: #6c757d;
            margin-top: 10px;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }

        .summary h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }

        .summary-stats {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 10px;
        }

        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .summary-label {
            font-size: 10px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PREDIKSI KELULUSAN MAHASISWA</h1>
        @if (!empty($filterInfo['prodi']) && !empty($filterInfo['angkatan']))
            <h2>{{ $filterInfo['prodi'] }} - Angkatan {{ $filterInfo['angkatan'] }}</h2>
        @elseif(!empty($filterInfo['prodi']))
            <h2>{{ $filterInfo['prodi'] }}</h2>
        @elseif(!empty($filterInfo['angkatan']))
            <h2>Angkatan {{ $filterInfo['angkatan'] }}</h2>
        @else
            <h2>Semua Data</h2>
        @endif
        <p class="subtitle">DeLusi - PEI</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Total Mahasiswa:</div>
                <div class="info-value">{{ $totalMahasiswa }} orang</div>
            </div>
            <div class="info-item">
                <div class="info-label">Dicetak pada:</div>
                <div class="info-value">{{ $generatedAt }}</div>
            </div>
        </div>
        @if (!empty($filterInfo['prodi']) || !empty($filterInfo['angkatan']))
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Filter yang diterapkan:</div>
                    <div class="info-value">
                        @if (!empty($filterInfo['prodi']))
                            Program Studi: {{ $filterInfo['prodi'] }}
                        @endif
                        @if (!empty($filterInfo['angkatan']))
                            @if (!empty($filterInfo['prodi']))
                                ,
                            @endif
                            Angkatan: {{ $filterInfo['angkatan'] }}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">NIM</th>
                    <th style="width: 25%;">Nama Mahasiswa</th>
                    <th style="width: 15%;">Program Studi</th>
                    <th style="width: 10%;">Angkatan</th>
                    <th style="width: 15%;">Hasil Prediksi</th>
                    <th style="width: 10%;">Confidence Score</th>
                    <th style="width: 10%;">Tanggal Prediksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['nim'] }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['prodi'] }}</td>
                        <td class="text-center">{{ $item['angkatan'] }}</td>
                        <td class="text-center">
                            @if ($item['hasil_prediksi'] == 'Tepat Waktu')
                                <span class="badge badge-success">{{ $item['hasil_prediksi'] }}</span>
                            @elseif($item['hasil_prediksi'] == 'Berisiko Tidak Tepat Waktu')
                                <span class="badge badge-danger">{{ $item['hasil_prediksi'] }}</span>
                            @elseif($item['hasil_prediksi'] == 'Belum Ada Prediksi')
                                <span class="badge badge-secondary">{{ $item['hasil_prediksi'] }}</span>
                            @else
                                <span class="badge badge-secondary">{{ $item['hasil_prediksi'] }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item['confidence_score'] != '-')
                                {{ $item['confidence_score'] }}%
                            @else
                                {{ $item['confidence_score'] }}
                            @endif
                        </td>
                        <td class="text-center">{{ $item['tanggal_prediksi'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data yang ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (!empty($data) && count($data) > 0)
        <div class="summary">
            <h4>Ringkasan Statistik</h4>
            <div class="summary-stats">
                <div class="summary-item">
                    <div class="summary-number">{{ $totalMahasiswa }}</div>
                    <div class="summary-label">Total Mahasiswa</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">
                        {{ collect($data)->where('hasil_prediksi', 'Tepat Waktu')->count() }}
                    </div>
                    <div class="summary-label">Tepat Waktu</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">
                        {{ collect($data)->where('hasil_prediksi', 'Berisiko Tidak Tepat Waktu')->count() }}
                    </div>
                    <div class="summary-label">Berisiko</div>
                </div>
            </div>
        </div>
    @endif

    <div class="footer">
        <p><strong>Catatan:</strong></p>
        <ul style="text-align: left; margin: 10px 0; padding-left: 20px;">
            <li>Laporan ini dihasilkan secara otomatis oleh sistem prediksi kelulusan</li>
            <li>Data prediksi berdasarkan model machine learning yang telah dilatih</li>
            <li>Confidence score menunjukkan tingkat kepercayaan model terhadap prediksi</li>
            <li>Laporan ini hanya untuk keperluan internal dan evaluasi akademik</li>
        </ul>
    </div>

    <div class="page-number">
        Halaman 1
    </div>
</body>

</html>

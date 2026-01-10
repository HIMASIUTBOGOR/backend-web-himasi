<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Registrations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .summary {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
        table thead th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        table tbody tr {
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        table tbody td {
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HIMASI Registration</h1>
        <p>Daftar Semua Pendaftaran Anggota</p>
    </div>

    <div class="summary">
        <p><strong>Total Pendaftaran:</strong> {{ $registrations->count() }}</p>
        <p><strong>Dicetak pada:</strong> {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    @if($registrations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama Lengkap</th>
                    <th style="width: 12%;">NIM</th>
                    <th style="width: 8%;">Semester</th>
                    <th style="width: 15%;">No. WhatsApp</th>
                    <th style="width: 25%;">Alasan</th>
                    <th style="width: 15%;">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $index => $registration)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $registration->fullname }}</td>
                        <td>{{ $registration->nim }}</td>
                        <td>{{ $registration->semester }}</td>
                        <td>{{ $registration->no_wa }}</td>
                        <td>{{ $registration->reason ?? '-' }}</td>
                        <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data pendaftaran</p>
        </div>
    @endif

    <div class="footer">
        <p>&copy; {{ date('Y') }} HIMASI - Himpunan Mahasiswa Sistem Informasi</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
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
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .content {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table tr {
            border-bottom: 1px solid #ddd;
        }
        table td {
            padding: 12px 10px;
            vertical-align: top;
        }
        table td:first-child {
            font-weight: bold;
            width: 30%;
            color: #555;
        }
        table td:last-child {
            color: #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HIMASI Registration</h1>
        <p>Detail Pendaftaran Anggota</p>
    </div>

    <div class="content">
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $registration->fullname }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: {{ $registration->nim }}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: {{ $registration->semester }}</td>
            </tr>
            <tr>
                <td>Nomor WhatsApp</td>
                <td>: {{ $registration->no_wa }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>: {{ $registration->department_id ? 'Departemen ID: ' . $registration->department_id : '-' }}</td>
            </tr>
            <tr>
                <td>Alasan Bergabung</td>
                <td>: {{ $registration->reason ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Daftar</td>
                <td>: {{ $registration->created_at->format('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>&copy; {{ date('Y') }} HIMASI - Himpunan Mahasiswa Sistem Informasi</p>
    </div>
</body>
</html>

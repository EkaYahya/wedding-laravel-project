<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Tamu On-The-Spot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        td {
            padding: 8px;
            font-size: 11px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Tamu On-The-Spot</h1>
        <div class="info">
            <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
            <p>Total Tamu: {{ count($spotGuests) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama</th>
                <th width="20%">Nomor WA</th>
                <th width="20%">Jenis Tamu</th>
                <th width="25%">Waktu Registrasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($spotGuests as $index => $guest)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $guest->name }}</td>
                    <td>{{ $guest->phone_number }}</td>
                    <td>{{ $guest->guest_type }}</td>
                    <td>{{ $guest->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ date('d-m-Y H:i:s') }} | Halaman 1</p>
    </div>
</body>
</html>
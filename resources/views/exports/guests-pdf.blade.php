<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu</title>
    <style>
        /* Definisi warna primer */
        :root {
            --primary-light: #A3D9E6;  /* Sesuaikan warna */
            --primary-dark: #3A6A8A;   /* Sesuaikan warna */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 2rem;
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: var(--primary-light);
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Daftar Tamu</h1>

        <table>
            <thead>
                <tr>
                    <th>Nama Tamu</th>
                    <th>Nomor Telepon</th>
                    <th>Jenis Tamu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guests as $guest)
                <tr>
                    <td>{{ $guest->name }}</td>
                    <td>{{ $guest->phone_number }}</td>
                    <td>{{ $guest->guest_type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

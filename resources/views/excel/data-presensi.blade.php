<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Data Presensi</title>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Lama Kerja</th>
                <th>Lokasi Masuk</th>
                <th>Lokasi Pulang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presensis as $presensi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $presensi->user->name ?? '' }}</td>
                    <td>{{ $presensi->tanggal }}</td>
                    <td>{{ $presensi->jam_masuk }}</td>
                    <td>{{ $presensi->jam_pulang }}</td>
                    <td>{{ $presensi->lama_kerja }}</td>
                    <td>{{ $presensi->lokasi_masuk }}</td>
                    <td>{{ $presensi->lokasi_pulang }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    <form action="{{ url('/mahasiswa') }}" method="GET">
        <label for="nim">Cari berdasarkan NIM:</label>
        <input
            type="text"
            id="nim"
            name="nim"
            value="{{ $nim ?? '' }}"
            placeholder="Masukkan NIM"
        >
        <button type="submit">Cari</button>
        <a href="{{ url('/mahasiswa') }}">Tampilkan Semua</a>
    </form>

    <hr>

    @if (count($daftarMahasiswa) > 0)
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Email</th>
                    <th>Usia</th>
                    <th>Program Studi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftarMahasiswa as $mhs)
                    <tr>
                        <td>{{ $mhs['nim'] }}</td>
                        <td>{{ $mhs['nama_mahasiswa'] }}</td>
                        <td>{{ $mhs['email'] }}</td>
                        <td>{{ $mhs['usia'] }}</td>
                        <td>{{ $mhs['nama_prodi'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif (!empty($nim))
        <p>Data mahasiswa dengan NIM {{ $nim }} tidak ditemukan.</p>
    @else
        <p>Belum ada data mahasiswa.</p>
    @endif
</body>
</html>

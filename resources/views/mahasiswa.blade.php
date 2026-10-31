
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>

    <h1>Data Mahasiswa</h1>

    @if (session('berhasil'))
        <p style="color: green;">{{ session('berhasil') }}</p>
    @endif

    @if (session('gagal'))
        <p style="color: red;">{{ session('gagal') }}</p>
    @endif

    <h2>Tambah Mahasiswa</h2>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf

        <p>
            <label>NIM</label><br>
            <input type="text" name="nim" value="{{ old('nim') }}" required>
        </p>

        <p>
            <label>Nama Mahasiswa</label><br>
            <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" required>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>

        <p>
            <label>Usia</label><br>
            <input type="number" name="usia" value="{{ old('usia') }}" required>
        </p>

        <p>
            <label>Program Studi</label><br>
            <select name="program_studi_id" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ old('program_studi_id') == $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Tambah Data</button>
    </form>

    <hr>

    <h2>Pencarian dan Filter</h2>

    <form action="{{ route('mahasiswa.index') }}" method="GET">
        <p>
            <label>Cari NIM, Nama, atau Email</label><br>
            <input type="text" name="q" value="{{ $q }}">
        </p>

        <p>
            <label>Program Studi</label><br>
            <select name="program_studi_id">
                <option value="">-- Semua Program Studi --</option>
                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ $programStudiId == $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Cari / Filter</button>
        <a href="{{ route('mahasiswa.index') }}">Reset</a>
    </form>

    <hr>

    <h2>Daftar Mahasiswa</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Email</th>
                <th>Usia</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($daftarMahasiswa as $index => $mahasiswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa['nim'] }}</td>
                    <td>{{ $mahasiswa['nama_mahasiswa'] }}</td>
                    <td>{{ $mahasiswa['email'] }}</td>
                    <td>{{ $mahasiswa['usia'] }}</td>
                    <td>{{ $mahasiswa['nama_prodi'] }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.edit', $mahasiswa['id']) }}">
                            Ubah
                        </a>

                        <form action="{{ route('mahasiswa.destroy', $mahasiswa['id']) }}"
                              method="POST"
                              style="display: inline;"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Data mahasiswa tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

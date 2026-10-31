
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
</head>
<body>

    <h1>Edit Data Mahasiswa</h1>

    @if (session('gagal'))
        <p style="color: red;">{{ session('gagal') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('mahasiswa.update', $mahasiswa['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>NIM</label><br>
            <input type="text" name="nim"
                   value="{{ old('nim', $mahasiswa['nim']) }}" required>
        </p>

        <p>
            <label>Nama Mahasiswa</label><br>
            <input type="text" name="nama_mahasiswa"
                   value="{{ old('nama_mahasiswa', $mahasiswa['nama_mahasiswa']) }}" required>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email"
                   value="{{ old('email', $mahasiswa['email']) }}" required>
        </p>

        <p>
            <label>Usia</label><br>
            <input type="number" name="usia"
                   value="{{ old('usia', $mahasiswa['usia']) }}" required>
        </p>

        <p>
            <label>Program Studi</label><br>
            <select name="program_studi_id" required>
                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ old('program_studi_id', $mahasiswa['program_studi_id']) == $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('mahasiswa.index') }}">Kembali</a>
    </form>

</body>
</html>

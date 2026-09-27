<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Mahasiswa</title>
</head>
<body>

    <h1>Form Mahasiswa</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <p>Terjadi kesalahan:</p>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('form.mahasiswa.submit') }}" method="POST">
        @csrf

        <div>
            <label for="nama">Nama:</label>
            <input
                type="text"
                id="nama"
                name="nama"
                value="{{ old('nama') }}"
            >
        </div>

        <br>

        <div>
            <label for="email">Email:</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
            >
        </div>

        <br>

        <div>
            <label for="usia">Usia:</label>
            <input
                type="number"
                id="usia"
                name="usia"
                value="{{ old('usia') }}"
            >
        </div>

        <br>

        <div>
            <label for="nim">NIM:</label>
            <input
                type="text"
                id="nim"
                name="nim"
                value="{{ old('nim') }}"
            >
        </div>

        <br>

        <button type="submit">Kirim</button>
    </form>

    @if (session('data'))
        <h2>Data yang Dikirim</h2>

        <p>Nama: {{ session('data.nama') }}</p>
        <p>Email: {{ session('data.email') }}</p>
        <p>Usia: {{ session('data.usia') }}</p>
        <p>NIM: {{ session('data.nim') }}</p>
    @endif

</body>
</html>
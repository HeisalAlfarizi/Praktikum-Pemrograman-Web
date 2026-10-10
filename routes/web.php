
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mahasiswa/{nim?}', function (?string $nim = null) {
    try {
        $pdo = DB::connection()->getPdo();

        $sql = 'SELECT
                    m.nim,
                    m.nama_mahasiswa,
                    m.email,
                    m.usia,
                    p.nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id';

        if ($nim !== null && $nim !== '') {
            $sql .= ' WHERE m.nim = :nim';
        }

        $sql .= ' ORDER BY m.nim';

        $statement = $pdo->prepare($sql);

        if ($nim !== null && $nim !== '') {
            $statement->execute(['nim' => $nim]);
        } else {
            $statement->execute();
        }

        $daftarMahasiswa = $statement->fetchAll(PDO::FETCH_ASSOC);

        return view('mahasiswa', [
            'daftarMahasiswa' => $daftarMahasiswa,
            'nim' => $nim,
            'error' => null,
        ]);

    } catch (\Throwable $error) {
        Log::error('Gagal mengambil data mahasiswa: ' . $error->getMessage());

        return response(
            'Koneksi atau query basis data gagal. Periksa konfigurasi database.',
            500
        );
    }
});

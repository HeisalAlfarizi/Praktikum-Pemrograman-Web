
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// READ, PENCARIAN, DAN FILTER
Route::get('/mahasiswa', function (Request $request) {
    try {
        $pdo = DB::connection()->getPdo();

        $q = $request->query('q');
        $programStudiId = $request->query('program_studi_id');

        $sql = 'SELECT
                    m.id,
                    m.nim,
                    m.nama_mahasiswa,
                    m.email,
                    m.usia,
                    p.nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id
                WHERE 1=1';

        $parameter = [];

        if ($q !== null && $q !== '') {
            $sql .= ' AND (
                m.nim LIKE :cari_nim
                OR m.nama_mahasiswa LIKE :cari_nama
                OR m.email LIKE :cari_email
            )';

            $kataKunci = '%' . $q . '%';

            $parameter['cari_nim'] = $kataKunci;
            $parameter['cari_nama'] = $kataKunci;
            $parameter['cari_email'] = $kataKunci;
        }

        if ($programStudiId !== null && $programStudiId !== '') {
            $sql .= ' AND m.program_studi_id = :program_studi_id';
            $parameter['program_studi_id'] = $programStudiId;
        }

        $sql .= ' ORDER BY m.nim';

        $statement = $pdo->prepare($sql);
        $statement->execute($parameter);

        $daftarMahasiswa = $statement->fetchAll(PDO::FETCH_ASSOC);

        $pernyataanProdi = $pdo->prepare(
            'SELECT id, nama_prodi FROM program_studi ORDER BY nama_prodi'
        );
        $pernyataanProdi->execute();

        $daftarProgramStudi = $pernyataanProdi->fetchAll(PDO::FETCH_ASSOC);

        return view('mahasiswa', [
            'daftarMahasiswa' => $daftarMahasiswa,
            'daftarProgramStudi' => $daftarProgramStudi,
            'q' => $q,
            'programStudiId' => $programStudiId,
        ]);

    } catch (\Throwable $error) {
        report($error);
        return response('Data tidak dapat dibaca. Periksa koneksi dan query.', 500);
    }
})->name('mahasiswa.index');


// CREATE
Route::post('/mahasiswa', function (Request $request) {
    $data = $request->validate([
        'nim' => ['required', 'string', 'max:20'],
        'nama_mahasiswa' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:100'],
        'usia' => ['required', 'integer', 'min:15', 'max:100'],
        'program_studi_id' => [
            'required',
            'integer',
            'exists:program_studi,id'
        ],
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        $cekDuplikat = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa
             WHERE nim = :nim OR email = :email'
        );

        $cekDuplikat->execute([
            'nim' => $data['nim'],
            'email' => $data['email'],
        ]);

        if ((int) $cekDuplikat->fetchColumn() > 0) {
            return back()->withInput()->with('gagal', 'NIM atau email sudah digunakan.');
        }

        $simpan = $pdo->prepare(
            'INSERT INTO mahasiswa
            (nim, nama_mahasiswa, email, usia, program_studi_id)
            VALUES
            (:nim, :nama_mahasiswa, :email, :usia, :program_studi_id)'
        );

        $simpan->execute($data);

        return redirect()->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil ditambahkan.');

    } catch (\Throwable $error) {
        report($error);
        return back()->withInput()->with('gagal', 'Data gagal disimpan.');
    }
})->name('mahasiswa.store');


// FORM EDIT
Route::get('/mahasiswa/{id}/edit', function ($id) {
    try {
        $pdo = DB::connection()->getPdo();

        $statement = $pdo->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $mahasiswa = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.index')
                ->with('gagal', 'Data mahasiswa tidak ditemukan.');
        }

        $prodi = $pdo->query(
            'SELECT id, nama_prodi FROM program_studi ORDER BY nama_prodi'
        )->fetchAll(PDO::FETCH_ASSOC);

        return view('mahasiswa-edit', [
            'mahasiswa' => $mahasiswa,
            'daftarProgramStudi' => $prodi,
        ]);

    } catch (\Throwable $error) {
        report($error);
        return back()->with('gagal', 'Data gagal dibaca.');
    }
})->name('mahasiswa.edit');


// UPDATE
Route::put('/mahasiswa/{id}', function (Request $request, $id) {
    $data = $request->validate([
        'nim' => ['required', 'string', 'max:20'],
        'nama_mahasiswa' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:100'],
        'usia' => ['required', 'integer', 'min:15', 'max:100'],
        'program_studi_id' => [
            'required',
            'integer',
            'exists:program_studi,id'
        ],
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        $cekDuplikat = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa
             WHERE (nim = :nim OR email = :email)
             AND id <> :id'
        );

        $cekDuplikat->execute([
            'nim' => $data['nim'],
            'email' => $data['email'],
            'id' => $id,
        ]);

        if ((int) $cekDuplikat->fetchColumn() > 0) {
            return back()->withInput()->with('gagal', 'NIM atau email sudah digunakan mahasiswa lain.');
        }

        $update = $pdo->prepare(
            'UPDATE mahasiswa SET
                nim = :nim,
                nama_mahasiswa = :nama_mahasiswa,
                email = :email,
                usia = :usia,
                program_studi_id = :program_studi_id
             WHERE id = :id'
        );

        $data['id'] = $id;
        $update->execute($data);

        return redirect()->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil diperbarui.');

    } catch (\Throwable $error) {
        report($error);
        return back()->withInput()->with('gagal', 'Data gagal diperbarui.');
    }
})->name('mahasiswa.update');


// DELETE
Route::delete('/mahasiswa/{id}', function ($id) {
    try {
        $pdo = DB::connection()->getPdo();

        $hapus = $pdo->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );

        $hapus->execute(['id' => $id]);

        if ($hapus->rowCount() > 0) {
            return redirect()->route('mahasiswa.index')
                ->with('berhasil', 'Data mahasiswa berhasil dihapus.');
        }

        return redirect()->route('mahasiswa.index')
            ->with('gagal', 'Data mahasiswa tidak ditemukan.');

    } catch (\Throwable $error) {
        report($error);
        return back()->with('gagal', 'Data gagal dihapus.');
    }
})->name('mahasiswa.destroy');

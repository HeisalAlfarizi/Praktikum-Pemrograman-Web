<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/latihan-php', function () { 
    $nama = 'Muhammad Heisal Alfarizi'; 
    $nilai = [99, 99, 99, 99, 100]; 
 
    $hitungRataRata = function (array $data): float { 
        $total = 0; 
        foreach ($data as $angka) { 
            $total += $angka; 
        } 
        return $total / count($data); 
    }; 
 
    $rataRata = $hitungRataRata($nilai); 
    if ($rataRata >= 75) { 
        $status = 'Lulus'; 
    } else { 
        $status = 'Perlu Perbaikan'; 
    } 
 
    return view('latihan-php', compact( 
        'nama', 'nilai', 'rataRata', 'status' 
    )); 
});

Route::get('/form-mahasiswa', function () {
    return view('form-mahasiswa');
})->name('form.mahasiswa');

Route::post('/form-mahasiswa', function (\Illuminate\Http\Request $request) {
    $request->merge([
        'nama' => trim($request->nama ?? ''),
        'email' => trim($request->email ?? ''),
        'nim' => trim($request->nim ?? ''),
    ]);

    $validated = $request->validate([
        'nama' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email'],
        'usia' => ['required', 'integer', 'min:1'],
        'nim' => ['required', 'digits_between:8,12'],
    ]);

    return back()->with('success', 'Data mahasiswa berhasil dikirim.');
})->name('form.mahasiswa.submit');
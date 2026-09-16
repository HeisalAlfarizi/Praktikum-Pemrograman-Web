<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/latihan-php', function () { 
    $nama = 'Muhammad Heisal Alfarizi'; 
    $nilai = [90, 95, 93, 88, 92]; 
 
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
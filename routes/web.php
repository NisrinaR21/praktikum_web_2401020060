<?php

use Illuminate\Support\Facades\Route;

// Route bawaan halaman utama (http://127.0.0.1:8000)
Route::get('/', function () {
    return view('welcome');
});

// Route Praktikum 2 (http://127.0.0.1:8000/latihan-php)
Route::get('/latihan-php', function () {
    $nama = 'Nisrina Retnosari';
    $nilai = [85, 90, 78, 88, 92]; // 5 nilai integer (Data 1: Rata-rata 86.60)

    $hitungRataRata = function (array $data): float {
        $total = 0;
        foreach ($data as $angka) {
            $total += $angka;
        }
        return $total / count($data);
    };

    $ratarata = $hitungRataRata($nilai);

    if ($ratarata >= 75) {
        $status = 'Lulus';
    } else {
        $status = 'Perlu Perbaikan';
    }

    return view('latihan-php', compact(
        'nama', 'nilai', 'ratarata', 'status'
    ));
});
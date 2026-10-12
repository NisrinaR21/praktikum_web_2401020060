<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Route untuk menampilkan semua mahasiswa ATAU filter berdasarkan NIM
Route::get('/mahasiswa/{nim?}', function (?string $nim = null) {
    try {
        // 1. Membuka koneksi PDO
        $pdo = DB::connection()->getPdo();
        
        // 2. Menyiapkan teks SQL dasar (Relasi INNER JOIN)
        $sql = 'SELECT m.nim, m.nama, m.email, m.usia, p.nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                ON p.id = m.program_studi_id';
                
        // 3. Menambahkan filter query parameter JIKA NIM diisi di URL
        if ($nim !== null) {
            $sql .= ' WHERE m.nim = :nim';
        }
        
        $sql .= ' ORDER BY m.nim';
        
        // 4. Prepared Statement (Mencegah SQL Injection)
        $statement = $pdo->prepare($sql);
        $statement->execute($nim !== null ? ['nim' => $nim] : []);
        
        // 5. Mengambil hasil query menjadi array asosiatif
        $daftarMahasiswa = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        // 6. Mengirim data ke tampilan Blade
        return view('mahasiswa', compact('daftarMahasiswa', 'nim'));
        
    } catch (\Throwable $error) {
        // 7. Error Handling jika koneksi/query gagal
        report($error);
        return response(
            'Koneksi atau query basis data gagal. Periksa file .env dan layanan MySQL.',
            500
        );
    }
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// 1. ROUTE READ: Nampilin form input dan daftar tabel mahasiswa
Route::get('/mahasiswa', function () {
    try {
        $pdo = DB::connection()->getPdo();

        // Ambil semua data mahasiswa gabung sama nama prodi
        $pernyataanMahasiswa = $pdo->prepare(
            'SELECT m.nim, m.nama, m.email, m.usia, p.nama_prodi
             FROM mahasiswa AS m
             JOIN program_studi AS p ON p.id = m.program_studi_id
             ORDER BY m.nim'
        );
        $pernyataanMahasiswa->execute();
        $daftarMahasiswa = $pernyataanMahasiswa->fetchAll(\PDO::FETCH_ASSOC);

        // Ambil data program studi untuk isi dropdown pilihan di form
        $pernyataanProdi = $pdo->prepare(
            'SELECT id, nama_prodi FROM program_studi ORDER BY nama_prodi'
        );
        $pernyataanProdi->execute();
        $daftarProgramStudi = $pernyataanProdi->fetchAll(\PDO::FETCH_ASSOC);

        return view('mahasiswa', compact('daftarMahasiswa', 'daftarProgramStudi'));
    } catch (\Throwable $error) {
        report($error);
        return response('Data tidak dapat dibaca. Periksa koneksi dan query.', 500);
    }
})->name('mahasiswa.index');

// 2. ROUTE CREATE: Validasi form, cek duplikat, dan simpan ke database
Route::post('/mahasiswa', function (Request $request) {
    // Validasi input form
    $data = $request->validate([
        'nim'              => ['required', 'string', 'max:20'],
        'nama'             => ['required', 'string', 'max:100'],
        'email'            => ['required', 'email', 'max:100'],
        'usia'             => ['required', 'integer', 'min:15', 'max:100'],
        'program_studi_id' => ['required', 'integer'],
    ], [
        'nim.required'              => 'NIM wajib diisi.',
        'nama.required'             => 'Nama wajib diisi.',
        'email.required'            => 'Email wajib diisi.',
        'email.email'               => 'Format email tidak valid.',
        'usia.required'             => 'Usia wajib diisi.',
        'usia.integer'              => 'Usia harus berupa angka.',
        'program_studi_id.required' => 'Program studi wajib dipilih.',
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        // Cegah data duplikat (cek apakah NIM atau Email udah pernah kedaftar)
        $cekDuplikat = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim OR email = :email'
        );
        $cekDuplikat->execute([
            'nim'   => $data['nim'],
            'email' => $data['email'],
        ]);

        if ((int) $cekDuplikat->fetchColumn() > 0) {
            return back()->withInput()->with('gagal', 'NIM atau email sudah digunakan.');
        }

        // Simpan data baru dengan prepared statement INSERT
        $simpan = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, usia, program_studi_id)
             VALUES (:nim, :nama, :email, :usia, :program_studi_id)'
        );
        $simpan->execute([
            'nim'              => $data['nim'],
            'nama'             => $data['nama'],
            'email'            => $data['email'],
            'usia'             => $data['usia'],
            'program_studi_id' => $data['program_studi_id'],
        ]);

        return redirect()->route('mahasiswa.index')->with('berhasil', 'Data mahasiswa berhasil ditambahkan.');
    } catch (\Throwable $error) {
        report($error);
        return back()->withInput()->with('gagal', 'Data gagal disimpan. Periksa koneksi dan data.');
    }
})->name('mahasiswa.store');
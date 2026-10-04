<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// 1. Route GET: Menampilkan form input mahasiswa
Route::get('/form-mahasiswa', function () {
    return view('form-mahasiswa');
});

// 2. Route POST: Memproses input, sanitasi, dan validasi
Route::post('/form-mahasiswa', function (Request $request) {
    // Tahap Sanitasi Data (membersihkan spasi dan tag HTML sebelum validasi)
    $dataBersih = [
        'nama'  => strip_tags(trim((string) $request->input('nama'))),
        'nim'   => trim((string) $request->input('nim')),
        'email' => filter_var((string) $request->input('email'), FILTER_SANITIZE_EMAIL),
        'usia'  => trim((string) $request->input('usia')),
    ];

    // Tahap Validasi Server-Side
    $validator = Validator::make($dataBersih, [
        'nama'  => ['required', 'min:3', 'max:50'],
        'nim'   => ['required', 'numeric', 'digits_between:8,12'],
        'email' => ['required', 'email'],
        'usia'  => ['required', 'integer', 'min:17', 'max:60'],
    ], [
        // Pesan Kesalahan Kustom
        'nama.required'      => 'Nama wajib diisi.',
        'nama.min'           => 'Nama minimal 3 karakter.',
        'nama.max'           => 'Nama maksimal 50 karakter.',
        'nim.required'       => 'NIM wajib diisi.',
        'nim.numeric'        => 'NIM harus berupa angka.',
        'nim.digits_between' => 'NIM harus terdiri dari 8 hingga 12 digit.',
        'email.required'     => 'Email wajib diisi.',
        'email.email'        => 'Format email tidak valid.',
        'usia.required'      => 'Usia wajib diisi.',
        'usia.integer'       => 'Usia harus berupa angka.',
        'usia.min'           => 'Usia minimal 17 tahun.',
        'usia.max'           => 'Usia maksimal 60 tahun.',
    ]);

    // Jika validasi gagal, kembali ke form dengan membawa error dan input lama
    if ($validator->fails()) {
        return redirect('/form-mahasiswa')
            ->withErrors($validator)
            ->withInput();
    }

    // Jika validasi sukses, ambil data yang telah tervalidasi
    $data = $validator->validated();
    $data['usia'] = (int) $data['usia'];

    return view('hasil-form', ['data' => $data]);
});
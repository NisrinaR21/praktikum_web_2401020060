USE praktikum_web_2401020060;

-- 1. Mengisi data awal tabel program_studi (minimal 2 data)
INSERT INTO program_studi (nama_prodi) VALUES
('Teknik Informatika'),
('Sistem Informasi');

-- 2. Mengisi data awal tabel mahasiswa (4 data: 3 data utama + 1 data sementara)
INSERT INTO mahasiswa (nim, nama, email, usia, program_studi_id) VALUES
('2401020060', 'Nisrina Retnosari', '2401020060@student.umrah.ac.id', 20, 1),
('2401020011', 'Anas Syawaludin', '2505020142@student.umrah.ac.id', 21, 1),
('2401020022', 'Muhammad Adib Haryadi', '2401020076@student.umrah.ac.id', 19, 2),
('2401020099', 'Dhiya Zarifa Putri Marzuki', '2401020075@student.umrah.ac.id', 18, 2);

-- 3. Memperbarui (UPDATE) data email Nisrina Retnosari
UPDATE mahasiswa
SET email = 'nisrinaretnosari@gmail.com'
WHERE nim = '2401020060';

-- 4. Menghapus (DELETE) data sementara sehingga menyisakan 3 data akhir
DELETE FROM mahasiswa
WHERE nim = '2401020075';

-- 5. Menampilkan data akhir dengan relasi INNER JOIN
SELECT m.nim, m.nama, m.email, m.usia, p.nama_prodi
FROM mahasiswa AS m
JOIN program_studi AS p ON p.id = m.program_studi_id
ORDER BY m.nim;
USE praktikum_web_2401020148;

-- Data program studi
INSERT INTO program_studi (id_prodi, nama_prodi) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi');

-- Data mahasiswa
INSERT INTO mahasiswa
(nim, nama_mahasiswa, email, usia, program_studi_id)
VALUES
('2401020148', 'Muhammad Heisal Alfarizi', '2401020148@student.umrah.ac.id', 20, 1),
('2401020152', 'Kristian Nugroho Keliat', '2401020152@student.umrah.ac.id', 33, 2),
('2401020159', 'Muhammad Dimaz Albintai', '2401020159@student.umrah.ac.id', 22, 2);

-- UPDATE
UPDATE mahasiswa
SET usia = 33
WHERE nim = '2401020152';

-- DELETE
DELETE FROM mahasiswa
WHERE nim = '2401020112';

-- SELECT JOIN
SELECT
    mahasiswa.nim,
    mahasiswa.nama_mahasiswa,
    mahasiswa.email,
    mahasiswa.usia,
    program_studi.nama_prodi
FROM mahasiswa
JOIN program_studi
    ON mahasiswa.program_studi_id = program_studi.id;
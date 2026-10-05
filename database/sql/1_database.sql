CREATE DATABASE IF NOT EXISTS praktikum_web_2401020148;

USE praktikum_web_2401020148;

CREATE TABLE program_studi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodi VARCHAR(10) NOT NULL UNIQUE,
    nama_prodi VARCHAR(100) NOT NULL
);

CREATE TABLE mahasiswa (
    nim VARCHAR(12) PRIMARY KEY,
    nama_mahasiswa VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    usia INT NOT NULL,
    program_studi_id INT NOT NULL,

    CONSTRAINT fk_mahasiswa_program_studi
        FOREIGN KEY (program_studi_id)
        REFERENCES program_studi(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
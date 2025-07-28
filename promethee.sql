CREATE DATABASE promethee_db;
USE promethee_db;

-- Tabel kriteria
CREATE TABLE kriteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    bobot FLOAT,
    tipe ENUM('benefit', 'cost')
);

-- Tabel alternatif
CREATE TABLE alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100)
);

-- Tabel nilai alternatif terhadap kriteria
CREATE TABLE nilai_alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alternatif INT,
    id_kriteria INT,
    nilai FLOAT,
    FOREIGN KEY (id_alternatif) REFERENCES alternatif(id),
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id)
);

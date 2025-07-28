
CREATE DATABASE IF NOT EXISTS spk_promethee;
USE spk_promethee;

CREATE TABLE IF NOT EXISTS alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS kriteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    bobot FLOAT NOT NULL,
    jenis ENUM('benefit','cost') NOT NULL
);

CREATE TABLE IF NOT EXISTS nilai_alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alternatif INT,
    id_kriteria INT,
    nilai FLOAT,
    FOREIGN KEY (id_alternatif) REFERENCES alternatif(id) ON DELETE CASCADE,
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hasil_rangking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alternatif INT,
    leaving FLOAT,
    entering FLOAT,
    net_flow FLOAT,
    ranking INT,
    FOREIGN KEY (id_alternatif) REFERENCES alternatif(id) ON DELETE CASCADE
);

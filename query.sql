CREATE DATABASE antri;

CREATE TABLE Dokter (
  ID_Dokter INT PRIMARY KEY AUTO_INCREMENT,
  Nama_D VARCHAR(50),
  Spesialis VARCHAR(20),
  Jadwal VARCHAR(30)
);

CREATE TABLE Pasien (
  ID_Pasien INT PRIMARY KEY AUTO_INCREMENT,
  NIK INT(20),
  Nama_P VARCHAR(50),
  Alamat VARCHAR(50),
  Domisili VARCHAR(50),
  Tgl_lahir DATE,
  No_HP VARCHAR(14),
  Jenis_Kelamin ENUM('L', 'P'),
  KTP_KK VARCHAR(50)
);

CREATE TABLE Resepsionis (
  ID_Resepsionis INT PRIMARY KEY AUTO_INCREMENT,
  Nama_R VARCHAR(50)
);

CREATE TABLE Antrian (
  ID_Antrian INT PRIMARY KEY AUTO_INCREMENT,
  ID_Resepsionis INT,
  ID_Pasien INT,
  ID_Dokter INT,
  Tgl_Antrian DATE,
  Status ENUM('Menunggu', 'Diperiksa', 'Selesai')
);

CREATE TABLE Antrian2 (
  ID_Antrian INT PRIMARY KEY AUTO_INCREMENT,
  ID_Resepsionis INT,
  ID_Pasien INT,
  ID_Dokter INT,
  Tgl_Antrian DATE,
  Status ENUM('Menunggu', 'Diperiksa', 'Selesai')
);

CREATE TABLE Antrian3 (
  ID_Antrian INT PRIMARY KEY AUTO_INCREMENT,
  ID_Resepsionis INT,
  ID_Pasien INT,
  ID_Dokter INT,
  Tgl_Antrian DATE,
  Status ENUM('Menunggu', 'Diperiksa', 'Selesai')
);

INSERT INTO Resepsionis (Nama_R) VALUES
('Linda Anderson'),
('Mark Thompson'),
('Jessica Lee'),
('Daniel Davis'),
('Michelle Wilson');

INSERT INTO Antrian (ID_Resepsionis, ID_Pasien, ID_Dokter, Tgl_Antrian, Status) VALUES
(1, 1, 1, '2023-07-01', 'Menunggu'),
(2, 2, 3, '2023-07-02', 'Diperiksa'),
(3, 3, 2, '2023-07-03', 'Selesai'),
(4, 4, 4, '2023-07-04', 'Menunggu'),
(5, 5, 5, '2023-07-05', 'Diperiksa');

INSERT INTO Antrian2 (ID_Resepsionis, ID_Pasien, ID_Dokter, Tgl_Antrian, Status) VALUES
(1, 1, 1, '2023-07-01', 'Menunggu'),
(2, 2, 2, '2023-07-02', 'Diperiksa'),
(3, 3, 3, '2023-07-03', 'Selesai'),
(4, 4, 4, '2023-07-04', 'Menunggu'),
(5, 5, 5, '2023-07-05', 'Diperiksa');

INSERT INTO Antrian3 (ID_Resepsionis, ID_Pasien, ID_Dokter, Tgl_Antrian, Status) VALUES
(1, 1, 1, '2023-07-01', 'Menunggu'),
(2, 2, 2, '2023-07-02', 'Diperiksa'),
(3, 3, 3, '2023-07-03', 'Selesai'),
(4, 4, 4, '2023-07-04', 'Menunggu'),
(5, 5, 5, '2023-07-05', 'Diperiksa');

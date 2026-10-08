-- Skema database UTS Pemrograman Web Lanjut
-- Nama database: pbl_ti_2025_a_ridhoalifsetiawan
-- (Kalau tabel sudah dibuat manual di phpMyAdmin, file ini nggak perlu dijalankan lagi.)

CREATE DATABASE IF NOT EXISTS pbl_ti_2025_a_ridhoalifsetiawan;
USE pbl_ti_2025_a_ridhoalifsetiawan;

CREATE TABLE account_type (
  id CHAR(36) PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  description TEXT,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL
);

CREATE TABLE actions (
  id CHAR(36) PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  description TEXT,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL
);

CREATE TABLE account (
  id CHAR(36) PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  email VARCHAR(128) NOT NULL UNIQUE,
  password VARCHAR(128) NOT NULL,
  account_type_id CHAR(36) NOT NULL,
  status VARCHAR(128) NOT NULL,
  identification_type ENUM('NIM','NIP') NOT NULL,
  identification_number VARCHAR(128) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  CONSTRAINT fk_account_account_type FOREIGN KEY (account_type_id) REFERENCES account_type(id)
);

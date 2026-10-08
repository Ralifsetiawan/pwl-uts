<?php

$host    = 'localhost';
$db_name = 'pbl_ti_2025_a_ridhoalifsetiawan';
$user    = 'root';
$pass    = '';

$conn = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// hasil query default-nya array asosiatif ($row['name'])
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

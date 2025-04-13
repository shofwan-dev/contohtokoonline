<?php
$host = 'localhost';    // Host database
$db   = 'belajarsql';   // Nama database
$user = 'root';         // Username database
$pass = '';             // Password database (kosong sesuai settings.json)
$charset = 'utf8mb4';   // Encoding karakter

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Konfigurasi Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opsi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Aktifkan error handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil query sebagai array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Nonaktifkan emulated prepares
];

try {
    // Membuat objek koneksi PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle error koneksi
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
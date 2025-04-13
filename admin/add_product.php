<?php
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean_input($_POST['name']);
    $harga = clean_input($_POST['price']);
    $deskripsi = clean_input($_POST['description']);
    $kategori = clean_input($_POST['category']);
    
    // Handle file upload
    $gambar = upload_image($_FILES['image']);
    
    if($gambar) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (nama, harga, deskripsi, gambar, kategori) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $harga, $deskripsi, $gambar, $kategori]);
            $_SESSION['success'] = "Produk berhasil ditambahkan!";
            header("Location: dashboard.php");
            exit;
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Tambah Produk Baru</h2>
        
        <?php include '../includes/alerts.php'; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" required>
            </div>
            
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="elektronik">Elektronik</option>
                    <option value="fashion">Fashion</option>
                    <option value="rumah-tangga">Rumah Tangga</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn-submit">Simpan Produk</button>
        </form>
    </div>
</body>
</html>
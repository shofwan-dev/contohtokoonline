<?php
require_once __DIR__ . '/includes/header.php';
include 'includes/db_connection.php';

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $name        = $_POST['name'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $category    = $_POST['category'];
    $stock       = $_POST['stock'];
    
    $image_url = $_FILES['image_url']['name'];
    $tmp_name  = $_FILES['image_url']['tmp_name'];

    // Jika user upload gambar baru
    if (!empty($image_url)) {
        move_uploaded_file($tmp_name, "assets/images/" . $image_url);

        $query = "UPDATE products SET 
                    name='$name',
                    price='$price',
                    description='$description',
                    category='$category',
                    stock='$stock',
                    image_url='$image_url'
                  WHERE id=$id";
    } else {
        // Tidak upload gambar baru, gambar tetap
        $query = "UPDATE products SET 
                    name='$name',
                    price='$price',
                    description='$description',
                    category='$category',
                    stock='$stock'
                  WHERE id=$id";
    }

    $update = mysqli_query($conn, $query);
    if ($update) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Update gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" class="form-control" value="<?= $row['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($row['category']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" value="<?= $row['stock'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini</label><br>
            <img src="assets/images/<?= htmlspecialchars($row['image_url']) ?>" width="150" alt="Gambar Produk"><br><br>
            <label class="form-label">Upload Gambar Baru (jika ingin ganti)</label>
            <input type="file" name="image_url" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Update Produk</button>
        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
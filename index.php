<?php
    require_once __DIR__ . '/includes/header.php';
    // Konfigurasi Database
    include 'includes/db_connection.php';

session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_GET['add_to_cart'])) {
    $id = $_GET['add_to_cart'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

// Ambil dan sanitasi input kategori
$selected_category = "";
if (isset($_GET['category'])) {
    $selected_category = mysqli_real_escape_string($conn, $_GET['category']);
}

// Query untuk mendapatkan kategori
$query_categories = "SELECT DISTINCT category FROM products ORDER BY category";
$result_categories = mysqli_query($conn, $query_categories);

// Handle error query kategori
if (!$result_categories) {
    die("<div class='alert alert-danger mt-4'>Error query kategori: " . mysqli_error($conn) . "</div>");
}

// Query produk dengan filter
$query_products = "SELECT * FROM products";
if (!empty($selected_category)) {
    $query_products .= " WHERE category = '$selected_category'";
}
$result_products = mysqli_query($conn, $query_products);

// Handle error query produk
if (!$result_products) {
    die("<div class='alert alert-danger mt-4'>Error query produk: " . mysqli_error($conn) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .product-image {
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid rgba(0,0,0,0.125);
        }
        .price-tag {
            font-size: 1.25rem;
            color: #198754;
            font-weight: bold;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(25, 135, 84, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">
    <main class="container my-5">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Filter Kategori:</label>
                                    <select class="form-select" name="category" onchange="this.form.submit()">
                                        <option value="">Semua Kategori</option>
                                        <?php while($category = mysqli_fetch_assoc($result_categories)): ?>
                                            <option value="<?= htmlspecialchars($category['category']) ?>" 
                                                <?= ($selected_category === $category['category']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['category']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <?php if(!empty($selected_category)): ?>
                                        <div class="alert alert-info mb-0">
                                            Filter aktif: <strong><?= htmlspecialchars($selected_category) ?></strong>
                                            <a href="index.php" class="btn btn-sm btn-outline-danger ms-2">Reset</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (mysqli_num_rows($result_products) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_products)): ?>
                    <div class="col">
                        <div class="card h-100 shadow">
                            <span class="category-badge"><?= htmlspecialchars($row['category']) ?></span>
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($row['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($row['description']) ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="price-tag">Rp <?= number_format($row['price'], 0, ',', '.') ?></span>
                                    </div>
                                    <div>
                                        <a href="?add_to_cart=<?= $row['id'] ?>" class="btn btn-primary w-100">Tambah ke Keranjang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center py-4">
                        <h4 class="mb-3">😞 Produk tidak ditemukan</h4>
                        <p class="mb-0">Silakan coba dengan filter yang berbeda</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/includes/footer.php';
// Tutup koneksi database
mysqli_close($conn);
?>
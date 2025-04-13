<?php
session_start();
require_once __DIR__ . '/includes/header.php';
include 'includes/db_connection.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Hapus item dari cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit;
}
?>

<h2>Keranjang Belanja</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Keranjang kosong.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty):
            $query = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
            $product = mysqli_fetch_assoc($query);
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;
        ?>
        <tr>
            <td><?= $product['name'] ?></td>
            <td>Rp<?= number_format($product['price']) ?></td>
            <td><?= $qty ?></td>
            <td>Rp<?= number_format($subtotal) ?></td>
            <td><a href="cart.php?remove=<?= $id ?>" class="btn btn-danger btn-sm">Hapus</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th colspan="2">Rp<?= number_format($total) ?></th>
            </tr>
        </tfoot>
    </table>
    <a href="checkout.php" class="btn btn-success">Checkout</a>
<?php endif; ?>

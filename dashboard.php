<?php
    require_once __DIR__ . '/includes/header.php';
    include 'includes/db_connection.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Product Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2>Product Dashboard</h2>
  <a href="add.php" class="btn btn-primary mb-3">+ Add Product</a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Price</th><th>Description</th><th>Image</th><th>Category</th><th>Stock</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT * FROM products");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>" . htmlspecialchars($row['id']) . "</td>
          <td>" . htmlspecialchars($row['name']) . "</td>
          <td>" . htmlspecialchars($row['price']) . "</td>
          <td>" . htmlspecialchars($row['description']) . "</td>
          <td>";
        if (!empty($row['image_url'])) {
          echo "<img src='assets/images/" . htmlspecialchars($row['image_url']) . "' width='50'>";
        } else {
          echo "<span class='text-muted'>No image</span>";
        }
        echo "</td>
          <td>" . htmlspecialchars($row['category']) . "</td>
          <td>" . htmlspecialchars($row['stock']) . "</td>
          <td>
            <a href='edit.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-sm btn-warning'>Edit</a>
            <a href='delete.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this product?')\">Delete</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

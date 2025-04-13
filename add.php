<?php 
    require_once __DIR__ . '/includes/header.php';
    include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $stock = $_POST['stock'];

  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];
  move_uploaded_file($tmp, "assets/images/" . $image);

  $conn->query("INSERT INTO products (name, price, description, image_url, category, stock)
                VALUES ('$name', '$price', '$description', '$image', '$category', '$stock')");

  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2>Add Product</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><input class="form-control" name="name" placeholder="Name" required></div>
    <div class="mb-3"><input class="form-control" name="price" type="number" step="0.01" placeholder="Price" required></div>
    <div class="mb-3"><textarea class="form-control" name="description" placeholder="Description"></textarea></div>
    <div class="mb-3"><input class="form-control" name="image" type="file" required></div>
    <div class="mb-3"><input class="form-control" name="category" placeholder="Category"></div>
    <div class="mb-3"><input class="form-control" name="stock" type="number" placeholder="Stock"></div>
    <button class="btn btn-success">Add</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>

</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

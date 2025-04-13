<?php
require_once __DIR__ . '/includes/header.php';
include 'includes/db_connection.php';
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: dashboard.php");
require_once __DIR__ . '/includes/footer.php';
?>

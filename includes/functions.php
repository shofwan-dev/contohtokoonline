<?php
require_once 'db_connection.php';

/**
 * Membersihkan dan memvalidasi input data
 */
function clean_input($data) {
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Mengelola upload gambar produk
 */
function upload_image($file) {
    $target_dir = "../assets/images/";
    
    // Buat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate nama file unik
    $timestamp = round(microtime(true) * 1000);
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_basename = pathinfo($file['name'], PATHINFO_FILENAME);
    $safe_filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $file_basename);
    $new_filename = "{$safe_filename}_{$timestamp}.{$file_extension}";
    $target_file = $target_dir . $new_filename;

    // Validasi file
    $max_size = 2 * 1024 * 1024; // 2MB
    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    
    // Cek error upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error uploading file. Code: " . $file['error']);
    }

    // Cek tipe file
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception("Hanya file JPG, JPEG, PNG & WEBP yang diperbolehkan.");
    }

    // Cek ukuran file
    if ($file['size'] > $max_size) {
        throw new Exception("Ukuran file melebihi batas 2MB.");
    }

    // Cek apakah file benar-benar gambar
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        throw new Exception("File yang diupload bukan gambar valid.");
    }

    // Pindahkan file ke direktori target
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $new_filename;
    } else {
        throw new Exception("Gagal menyimpan file. Coba lagi.");
    }
}

/**
 * Mengambil data produk dari database
 */
function get_products($category = 'all', $limit = 20, $offset = 0) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM products";
        $params = [];
        
        if ($category !== 'all') {
            $sql .= " WHERE kategori = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

/**
 * Menampilkan notifikasi dari session
 */
function display_alerts() {
    if (isset($_SESSION['success'])) {
        echo '<div class="alert success">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error'])) {
        echo '<div class="alert error">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
}

/**
 * Validasi login admin
 */
function authenticate_admin($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            return true;
        }
        return false;
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

/**
 * Memastikan user sudah login
 */
function require_login() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Generate pagination
 */
function generate_pagination($current_page, $total_pages) {
    $pagination = '<div class="pagination">';
    
    if ($total_pages > 1) {
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $current_page) ? 'active' : '';
            $pagination .= "<a href='?page=$i' class='$active'>$i</a>";
        }
    }
    
    $pagination .= '</div>';
    return $pagination;
}
?>
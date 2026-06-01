<?php
include 'includes/db.php';
include 'includes/header.php';

// Jika user sudah login, langsung lempar ke halaman utama (dashboard.php)
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password biner/hash
        if (password_verify($password, $user['password'])) {
            // Simpan data login ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // PROSES TRANSISI HALAMAN (REDIRECT) YANG BENAR
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin/index.php"); // Admin ke Panel Admin
            } else {
                header("Location: dashboard.php"); // Member ke Home Publik (dashboard.php)
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<div class="form-container" style="max-width: 400px; margin: 5px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 20px; color: #2c3e50;">Login Member / Admin</h2>
    
    <?php if ($error != ''): ?>
        <div class="alert-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; text-align: center;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Username</label>
            <input type="text" name="username" id="username" required autocomplete="off" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <button type="submit" class="btn" style="width: 100%; padding: 10px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold;">Masuk</button>
        
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Belum punya akun? <a href="register.php" style="color: #3498db; text-decoration: none; font-weight: bold;">Daftar di sini</a>
        </p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

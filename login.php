<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_samesite', 'None');
    ini_set('session.cookie_secure', '0');
    session_start();
}

// Jika user sudah login, langsung lempar ke halaman utama
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

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($_SESSION['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}

include 'includes/header.php';
?>

<div class="login-page">

    <!-- KIRI -->
    <div class="login-left">

        <div class="overlay-content">

    <div class="brand">
        <h1>Tavern </h1>
        <p>  Of Meeple<p>
    </div>

    <div class="welcome-text">
        <h2>
            Perpaduan sempurna antara kedalaman taktis dan koneksi digital yang mulus.
        </h2>

        <p>
            Bergabunglah dengan komunitas eksklusif pemain. 
            Kelola koleksimu, temukan taktik baru, dan atur malam permainanmu berikutnya.
        </p>
    </div>

</div>

    </div>

    <!-- KANAN -->
    <div class="login-right">

        <div class="login-card">

            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Enter your details to access your dashboard.</p>
            </div>

            <?php if ($error != ''): ?>
                <div class="alert-error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        required
                        autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        required>
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                </button>

                <div class="register-link">
                    Belum punya akun?
                    <a href="register.php">Daftar di sini</a>
                </div>

            </form>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

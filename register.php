<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika user sudah login, lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'member';

    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = db_get_result($stmt_check);

        if ($result_check->num_rows > 0) {
            $error = "Username sudah terdaftar! Silakan cari nama lain.";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $password_hashed, $role);

            if ($stmt_insert->execute()) {
                $success = "Registrasi BERHASIL! Silakan <a href='login.php'>Login di sini</a>.";
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="login-page">

    <!-- FORM REGISTER (KIRI) -->
    <div class="login-right">

        <div class="login-card">

            <div class="login-header">
                <h2>Welcome</h2>
                <p>Create your account to get started.</p>
            </div>

            <?php if ($error != ''): ?>
                <div class="alert-error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success != ''): ?>
                <div class="alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        required
                        autocomplete="off"
                        minlength="4"
                        placeholder="Minimum 4 characters">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        minlength="6"
                        placeholder="Minimum 6 characters">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        id="confirm_password"
                        required
                        placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn-login">
                    Daftar
                </button>

                <div class="register-link">
                    Sudah punya akun?
                    <a href="login.php">Login di sini</a>
                </div>

            </form>

        </div>

    </div>

    <!-- KANAN (dekorasi) -->
    <div class="login-left">

        <div class="overlay-content">

            <div class="brand">
                <h1>Tavern</h1>
                <p>Of Meeple</p>
            </div>

            <div class="welcome-text">
                <h2>
                    Mulai petualangan board game-mu hari ini.
                </h2>
                <p>
                    Daftarkan dirimu dan bergabunglah dengan komunitas pemain board game terbaik.
                </p>
            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

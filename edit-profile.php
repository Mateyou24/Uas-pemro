<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = db_get_result($stmt)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bio = $_POST['bio'];
    $avatar = $user['avatar'];

    if (
        isset($_FILES['avatar']) &&
        $_FILES['avatar']['error'] == 0
    ) {
        $folder = "assets/avatars/";
        $avatar = time() . "_" . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
    }

    $update = $conn->prepare("UPDATE users SET bio = ?, avatar = ? WHERE id = ?");
    $update->bind_param("ssi", $bio, $avatar, $user_id);
    $update->execute();

    header("Location: profile.php");
    exit;
}

include 'includes/header.php';
?>

<div class="edit-profile-container">

    <div class="edit-profile-card">

        <h1>Edit Profil</h1>

        <form
            method="POST"
            enctype="multipart/form-data"
            class="edit-profile-form"
        >

            <!-- KIRI -->
            <div class="edit-left">

                <img
                    src="assets/avatars/<?=
                    !empty($user['avatar'])
                    ? htmlspecialchars($user['avatar'])
                    : 'default-avatar.png';
                    ?>"
                    class="edit-avatar-preview"
                    alt="Avatar"
                >

                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    accept="image/*"
                >

            </div>

            <!-- KANAN -->
            <div class="edit-right">

                <div class="form-group">

                    <label>Tambah Bio</label>

                    <textarea
                        name="bio"
                        rows="6"
                        placeholder="Ceritakan sedikit tentang dirimu..."
                    ><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>

                </div>

                <button
                    type="submit"
                    class="save-profile-btn"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

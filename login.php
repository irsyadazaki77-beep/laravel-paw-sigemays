<?php
session_start();
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/functions.php';

if (!empty($_SESSION['username'])) {
    header('Location: student_view.php');
    exit;
}
$errorMessage = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = $connection->prepare('SELECT password FROM admin WHERE username = ?');
    $statement->bind_param('s', $username);
    $statement->execute();
    $statement->bind_result($storedHash);
    $found = $statement->fetch();
    $statement->close();

    $validPassword = $found && password_verify($password, $storedHash);
    $legacyPassword = $found && hash_equals($storedHash, sha1($password));
    if ($validPassword || $legacyPassword) {
        if ($legacyPassword && !$validPassword) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $connection->prepare('UPDATE admin SET password = ? WHERE username = ?');
            $update->bind_param('ss', $newHash, $username);
            $update->execute();
            $update->close();
        }
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        header('Location: student_view.php');
        exit;
    }
    $errorMessage = 'Username atau password tidak sesuai.';
}
$pageTitle = 'Login Data Mahasiswa';
?>
<!doctype html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($pageTitle) ?></title><link rel="stylesheet" href="assets/login.css"></head>
<body>
<main class="login-card">
    <h1>Data Mahasiswa</h1>
    <p>Masuk untuk mengelola data mahasiswa.</p>
    <?php if ($errorMessage !== ''): ?><div class="error"><?= e($errorMessage) ?></div><?php endif; ?>
    <form action="login.php" method="post">
        <input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="username">Username</label>
        <input required type="text" name="username" id="username" autocomplete="username" value="<?= e($username) ?>">
        <label for="password">Password</label>
        <input required type="password" name="password" id="password" autocomplete="current-password">
        <button type="submit">Log In</button>
    </form>
    <p class="hint">Database belum disiapkan? Jalankan <a href="generate.php">generate.php</a> terlebih dahulu.</p>
</main>
</body>
</html>
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Edit Mahasiswa';
$originalNim = trim((string) ($_GET['nim'] ?? $_POST['original_nim'] ?? ''));
$statement = $connection->prepare('SELECT nim, name, birth_city, birth_date, faculty, department, gpa FROM student WHERE nim = ?');
$statement->bind_param('s', $originalNim);
$statement->execute();
$result = $statement->get_result();
$existing = $result->fetch_assoc();
$statement->close();
if (!$existing) {
    http_response_code(404);
    exit('Data mahasiswa tidak ditemukan. <a href="student_view.php">Kembali</a>');
}
$student = [
    'nim' => $existing['nim'], 'name' => $existing['name'], 'birth_city' => $existing['birth_city'],
    'birth_day' => date('j', strtotime($existing['birth_date'])),
    'birth_month' => date('n', strtotime($existing['birth_date'])),
    'birth_year' => date('Y', strtotime($existing['birth_date'])),
    'faculty' => $existing['faculty'], 'department' => $existing['department'], 'gpa' => $existing['gpa'],
];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $student = student_input_from_post();
    $errors = validate_student_input($student, $connection, $originalNim);
    if (!$errors) {
        $birthDate = birth_date_from_input($student);
        $gpa = (float) $student['gpa'];
        $statement = $connection->prepare('UPDATE student SET nim = ?, name = ?, birth_city = ?, birth_date = ?, faculty = ?, department = ?, gpa = ? WHERE nim = ?');
        $statement->bind_param('ssssssds', $student['nim'], $student['name'], $student['birth_city'], $birthDate, $student['faculty'], $student['department'], $gpa, $originalNim);
        $statement->execute();
        $statement->close();
        set_flash('Data mahasiswa ' . $student['name'] . ' berhasil diperbarui.');
        header('Location: student_view.php');
        exit;
    }
}
require __DIR__ . '/includes/header.php';
?>
    <h2>Edit Data Mahasiswa</h2>
    <?php if ($errors): ?><div class="error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form class="form-card" action="student_edit.php" method="post">
        <input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="original_nim" value="<?= e($originalNim) ?>">
        <?php require __DIR__ . '/includes/student_form_fields.php'; ?>
        <button type="submit">Simpan Perubahan</button>
        <a class="button secondary" href="student_view.php">Batal</a>
    </form>
<?php require __DIR__ . '/includes/footer.php'; ?>
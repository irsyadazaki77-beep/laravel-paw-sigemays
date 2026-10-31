<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Tambah Mahasiswa';
$student = ['nim' => '', 'name' => '', 'birth_city' => '', 'birth_day' => '1', 'birth_month' => '1', 'birth_year' => '1996', 'faculty' => 'FTIB', 'department' => '', 'gpa' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $student = student_input_from_post();
    $errors = validate_student_input($student, $connection);
    if (!$errors) {
        $birthDate = birth_date_from_input($student);
        $gpa = (float) $student['gpa'];
        $statement = $connection->prepare('INSERT INTO student (nim, name, birth_city, birth_date, faculty, department, gpa) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->bind_param('ssssssd', $student['nim'], $student['name'], $student['birth_city'], $birthDate, $student['faculty'], $student['department'], $gpa);
        $statement->execute();
        $statement->close();
        set_flash('Data mahasiswa ' . $student['name'] . ' berhasil ditambahkan.');
        header('Location: student_view.php');
        exit;
    }
}
require __DIR__ . '/includes/header.php';
?>
    <h2>Tambah Data Mahasiswa</h2>
    <?php if ($errors): ?><div class="error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form class="form-card" action="student_add.php" method="post">
        <input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">
        <?php require __DIR__ . '/includes/student_form_fields.php'; ?>
        <button type="submit">Tambah Data</button>
    </form>
<?php require __DIR__ . '/includes/footer.php'; ?>
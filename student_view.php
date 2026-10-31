<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Data Mahasiswa';
$message = take_flash();
$result = $connection->query('SELECT nim, name, birth_city, birth_date, faculty, department, gpa FROM student ORDER BY nim ASC');
require __DIR__ . '/includes/header.php';
?>
    <h2>Daftar Mahasiswa</h2>
    <?php if ($message !== null): ?><div class="notice"><?= e($message) ?></div><?php endif; ?>
    <div class="table-wrap">
        <table>
            <thead><tr><th>NIM</th><th>Nama</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>Fakultas</th><th>Jurusan</th><th>IPK</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php while ($student = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= e($student['nim']) ?></td><td><?= e($student['name']) ?></td><td><?= e($student['birth_city']) ?></td>
                    <td><?= e(date('d-m-Y', strtotime($student['birth_date']))) ?></td><td><?= e($student['faculty']) ?></td>
                    <td><?= e($student['department']) ?></td><td><?= e(number_format((float) $student['gpa'], 2)) ?></td>
                    <td class="actions">
                        <a class="button small" href="student_edit.php?nim=<?= rawurlencode($student['nim']) ?>">Edit</a>
                        <form action="student_delete.php" method="post" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                            <input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="nim" value="<?= e($student['nim']) ?>">
                            <button class="button danger small" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php $result->free(); require __DIR__ . '/includes/footer.php'; ?>

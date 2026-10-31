<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Data Mahasiswa';
$message = take_flash();

// Feature Mahasiswa A (Zaki): Query data dan statistik mahasiswa
$result = $connection->query('SELECT nim, name, birth_city, birth_date, faculty, department, gpa FROM student ORDER BY nim ASC');
$totalStudents = $result->num_rows;
$sumGpa = 0.0;
$studentsData = [];
while ($row = $result->fetch_assoc()) {
    $sumGpa += (float)$row['gpa'];
    $studentsData[] = $row;
}
$avgGpa = $totalStudents > 0 ? round($sumGpa / $totalStudents, 2) : 0.0;

require __DIR__ . '/includes/header.php';
?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Daftar Mahasiswa</h2>
        <div style="font-size: 0.9rem; color: #4b5563; background: #e5e7eb; padding: 6px 14px; border-radius: 6px;">
            Total Mahasiswa: <strong><?= $totalStudents ?></strong> | Rata-rata IPK: <strong><?= number_format($avgGpa, 2) ?></strong>
        </div>
    </div>

    <?php if ($message !== null): ?><div class="notice"><?= e($message) ?></div><?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead><tr><th>NIM</th><th>Nama</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>Fakultas</th><th>Jurusan</th><th>IPK</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($studentsData)): ?>
                <tr><td colspan="8" style="text-align:center; padding: 20px;">Belum ada data mahasiswa. Silakan tambah data baru.</td></tr>
            <?php else: ?>
                <?php foreach ($studentsData as $student): ?>
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
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px; font-size: 0.8rem; color: #6b7280; text-align: right;">
        <em>Modul Tampil & Statistik dikembangkan oleh: <strong>Zaki Irsyada Putra (Mahasiswa A - Branch: zaki)</strong></em>
    </div>

<?php $result->free(); require __DIR__ . '/includes/footer.php'; ?>

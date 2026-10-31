<p>
    <label for="nim">NIM</label><br>
    <input required pattern="[0-9]{8}" maxlength="8" inputmode="numeric" type="text" name="nim" id="nim" value="<?= e($student['nim'] ?? '') ?>">
    <small>8 digit angka</small>
</p>
<p><label for="name">Nama</label><br><input required type="text" name="name" id="name" value="<?= e($student['name'] ?? '') ?>"></p>
<p><label for="birth_city">Tempat Lahir</label><br><input required type="text" name="birth_city" id="birth_city" value="<?= e($student['birth_city'] ?? '') ?>"></p>
<p>
    <label>Tanggal Lahir</label><br>
    <select name="birth_day" aria-label="Tanggal">
        <?php for ($i = 1; $i <= 31; $i++): ?><option value="<?= $i ?>" <?= (int) ($student['birth_day'] ?? 1) === $i ? 'selected' : '' ?>><?= sprintf('%02d', $i) ?></option><?php endfor; ?>
    </select>
    <select name="birth_month" aria-label="Bulan">
        <?php foreach ($monthNames as $number => $label): ?><option value="<?= $number ?>" <?= (int) ($student['birth_month'] ?? 1) === $number ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
    </select>
    <select name="birth_year" aria-label="Tahun">
        <?php for ($i = 1990; $i <= 2005; $i++): ?><option value="<?= $i ?>" <?= (int) ($student['birth_year'] ?? 1996) === $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?>
    </select>
</p>
<p>
    <label for="faculty">Fakultas</label><br>
    <select name="faculty" id="faculty">
        <option value="FTIB" <?= ($student['faculty'] ?? 'FTIB') === 'FTIB' ? 'selected' : '' ?>>FTIB</option>
        <option value="FTEIC" <?= ($student['faculty'] ?? '') === 'FTEIC' ? 'selected' : '' ?>>FTEIC</option>
    </select>
</p>
<p><label for="department">Jurusan</label><br><input required type="text" name="department" id="department" value="<?= e($student['department'] ?? '') ?>"></p>
<p><label for="gpa">IPK</label><br><input required type="number" min="0" max="4" step="0.01" name="gpa" id="gpa" value="<?= e($student['gpa'] ?? '') ?>"></p>

<?php
include 'koneksi.php';

// Ambil data alternatif dan kriteria
$alternatif = $koneksi->query("SELECT * FROM alternatif");
$kriteria = $koneksi->query("SELECT * FROM kriteria");

if (isset($_POST['simpan'])) {
    $id_alternatif = $_POST['id_alternatif'];
    foreach ($_POST['nilai'] as $id_kriteria => $nilai) {
        // Cek apakah nilai sudah ada
        $cek = $koneksi->query("SELECT * FROM nilai_alternatif WHERE id_alternatif=$id_alternatif AND id_kriteria=$id_kriteria");
        if ($cek->num_rows > 0) {
            $koneksi->query("UPDATE nilai_alternatif SET nilai=$nilai WHERE id_alternatif=$id_alternatif AND id_kriteria=$id_kriteria");
        } else {
            $koneksi->query("INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ($id_alternatif, $id_kriteria, $nilai)");
        }
    }
    header("Location: nilai.php?id_alternatif=$id_alternatif");
    exit;
}

$id_alternatif = $_GET['id_alternatif'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Input Nilai Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">
        <span class="badge text-bg-light text-dark p-2 shadow-sm">
            Input Nilai Siswa Berdasarkan Kriteria
        </span>
    </h2>

    <!-- Pilih Alternatif -->
    <form method="get" class="mb-4 row g-2 align-items-center">
        <div class="col-md-4">
            <select name="id_alternatif" class="form-select" onchange="this.form.submit()" required>
                <option value="">-- Pilih Nama Siswa --</option>
                <?php while ($row = $alternatif->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= $id_alternatif == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <?php if ($id_alternatif): 
        // Ambil nilai existing
        $nilai_existing = [];
        $res = $koneksi->query("SELECT * FROM nilai_alternatif WHERE id_alternatif=$id_alternatif");
        while ($row = $res->fetch_assoc()) {
            $nilai_existing[$row['id_kriteria']] = $row['nilai'];
        }
        // Refresh kriteria
        $kriteria = $koneksi->query("SELECT * FROM kriteria");
    ?>
    <form method="post">
        <input type="hidden" name="id_alternatif" value="<?= $id_alternatif ?>" />
        <table class="table table-bordered w-100">
            <thead class="table-primary text-center">
                <tr><th>Kriteria</th><th>Nilai</th></tr>
            </thead>
            <tbody>
                <?php while ($row = $kriteria->fetch_assoc()): ?>
                <tr>
                    <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                    <td width="25%">
                        <input 
                          type="number" step="0.01" min="0" max="100"
                          name="nilai[<?= $row['id'] ?>]" 
                          value="<?= $nilai_existing[$row['id']] ?? '' ?>" 
                          class="form-control text-center" required />
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="simpan" class="btn btn-success mt-3">
            <i class="fa fa-save me-1"></i> Simpan Nilai
        </button>
    </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

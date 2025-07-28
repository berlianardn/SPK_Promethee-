<?php
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $bobot = floatval($_POST['bobot']);
    $jenis = $_POST['jenis'];
    $koneksi->query("INSERT INTO kriteria (nama, bobot, jenis) VALUES ('$nama', $bobot, '$jenis')");
    header('Location: kriteria.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM kriteria WHERE id=$id");
    header('Location: kriteria.php');
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $bobot = floatval($_POST['bobot']);
    $jenis = $_POST['jenis'];
    $koneksi->query("UPDATE kriteria SET nama='$nama', bobot=$bobot, jenis='$jenis' WHERE id=$id");
    header('Location: kriteria.php');
    exit;
}

$kriteria = $koneksi->query("SELECT * FROM kriteria");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Data Kriteria - Menentukan Rangking Siswa Terbaik</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">
        <span class="badge text-bg-light text-dark p-2 shadow-sm">
            Kriteria Penilaian Siswa
        </span>
    </h2>
    
    <!-- Form Tambah -->
    <form method="post" class="mb-4 row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="nama" class="form-control" placeholder="Nama Kriteria" required />
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="bobot" class="form-control" placeholder="Bobot" required />
        </div>
        <div class="col-md-3">
            <select name="jenis" class="form-select" required>
                <option value="" disabled selected>Jenis</option>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" name="tambah" class="btn btn-success w-100">
                <i class="fa fa-plus me-1"></i> Tambah
            </button>
        </div>
    </form>

    <!-- Tabel Kriteria -->
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th>#</th>
                <th>Nama Kriteria</th>
                <th>Bobot</th>
                <th>Jenis</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php $no = 1; while ($row = $kriteria->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['bobot'] ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit">
                        <i class="fa fa-pen"></i>
                    </button>
                    <a href="kriteria.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus kriteria ini?')" class="btn btn-danger btn-sm" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </a>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editLabel<?= $row['id'] ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <form method="post" class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editLabel<?= $row['id'] ?>">Edit Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                              <input type="text" name="nama" class="form-control mb-2" value="<?= htmlspecialchars($row['nama']) ?>" required />
                              <input type="number" step="0.01" name="bobot" class="form-control mb-2" value="<?= $row['bobot'] ?>" required />
                              <select name="jenis" class="form-select" required>
                                <option value="benefit" <?= $row['jenis']=='benefit' ? 'selected' : '' ?>>Benefit</option>
                                <option value="cost" <?= $row['jenis']=='cost' ? 'selected' : '' ?>>Cost</option>
                              </select>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="edit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include 'koneksi.php';

// CRUD Alternatif
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $koneksi->query("INSERT INTO alternatif (nama) VALUES ('$nama')");
    header('Location: alternatif.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM alternatif WHERE id=$id");
    header('Location: alternatif.php');
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $koneksi->query("UPDATE alternatif SET nama='$nama' WHERE id=$id");
    header('Location: alternatif.php');
    exit;
}

$alternatif = $koneksi->query("SELECT * FROM alternatif");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Menentukan Rangking Siswa Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">
        <span class="badge text-bg-light text-dark p-2 shadow-sm">
            Menentukan Rangking Siswa Terbaik
        </span>
    </h2>

    <!-- Form Tambah -->
    <form method="post" class="mb-4 row g-2 align-items-center">
        <div class="col-md-10">
            <input type="text" name="nama" class="form-control" placeholder="Nama Siswa (Alternatif)" required />
        </div>
        <div class="col-md-2">
            <button type="submit" name="tambah" class="btn btn-success w-100">
                <i class="fa fa-plus me-1"></i> Tambah
            </button>
        </div>
    </form>

    <!-- Tabel Alternatif -->
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th width="5%">#</th>
                <th>Nama Alternatif</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php $no = 1; while ($row = $alternatif->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit">
                        <i class="fa fa-pen"></i>
                    </button>
                    <a href="alternatif.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </a>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editLabel<?= $row['id'] ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <form method="post" class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editLabel<?= $row['id'] ?>">Edit Nama Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                              <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required />
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

<!-- Bootstrap + FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

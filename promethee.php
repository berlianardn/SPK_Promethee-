<?php
include 'koneksi.php';

// Ambil data alternatif dan kriteria
$alternatif = [];
$resAlt = $koneksi->query("SELECT * FROM alternatif");
while ($row = $resAlt->fetch_assoc()) {
    $alternatif[$row['id']] = $row['nama'];
}

$kriteria = [];
$resKri = $koneksi->query("SELECT * FROM kriteria");
while ($row = $resKri->fetch_assoc()) {
    $kriteria[$row['id']] = [
        'nama' => $row['nama'],
        'bobot' => $row['bobot'],
        'jenis' => $row['jenis']
    ];
}

// Ambil nilai alternatif per kriteria
$nilai = [];
foreach ($alternatif as $idAlt => $namaAlt) {
    $resNilai = $koneksi->query("SELECT * FROM nilai_alternatif WHERE id_alternatif=$idAlt");
    while ($row = $resNilai->fetch_assoc()) {
        $nilai[$idAlt][$row['id_kriteria']] = $row['nilai'];
    }
}

// Fungsi untuk hitung preferensi antara dua alternatif
function preferensi($d, $jenis) {
    return ($jenis == 'benefit' && $d > 0) || ($jenis == 'cost' && $d < 0) ? 1 : 0;
}

// Matriks preferensi
$dij = [];
$n = count($nilai);
$ids = array_keys($nilai);

for ($i = 0; $i < $n; $i++) {
    for ($j = 0; $j < $n; $j++) {
        if ($i == $j) {
            $dij[$ids[$i]][$ids[$j]] = 0;
            continue;
        }
        $sum = 0;
        foreach ($kriteria as $idk => $kri) {
            $diff = $nilai[$ids[$i]][$idk] - $nilai[$ids[$j]][$idk];
            $pref = preferensi($diff, $kri['jenis']);
            $sum += $kri['bobot'] * $pref;
        }
        $dij[$ids[$i]][$ids[$j]] = $sum;
    }
}

// Hitung leaving, entering, net flow
$leaving = [];
$entering = [];
$netflow = [];

for ($i = 0; $i < $n; $i++) {
    $leaving[$ids[$i]] = 0;
    $entering[$ids[$i]] = 0;
    for ($j = 0; $j < $n; $j++) {
        if ($i == $j) continue;
        $leaving[$ids[$i]] += $dij[$ids[$i]][$ids[$j]];
        $entering[$ids[$i]] += $dij[$ids[$j]][$ids[$i]];
    }
    $leaving[$ids[$i]] /= ($n - 1);
    $entering[$ids[$i]] /= ($n - 1);
    $netflow[$ids[$i]] = $leaving[$ids[$i]] - $entering[$ids[$i]];
}

// Urutkan berdasarkan Net Flow
arsort($netflow);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Rangking Siswa Terbaik - PROMETHEE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
<style>
    table th, table td {
        vertical-align: middle !important;
    }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">
        <span class="badge text-bg-light text-dark p-2 shadow-sm">
            Hasil Perhitungan dan Rangking Siswa Terbaik (PROMETHEE)
        </span>
    </h2>

    <!-- Matriks Preferensi -->
    <h5 class="mt-4">Matriks Preferensi (d<sub>ij</sub>)</h5>
    <table class="table table-bordered table-striped table-sm w-auto text-center">
        <thead class="table-secondary">
            <tr>
                <th>Alternatif</th>
                <?php foreach ($alternatif as $namaAlt): ?>
                    <th><?= htmlspecialchars($namaAlt) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dij as $idA => $row): ?>
            <tr>
                <th class="text-start"><?= htmlspecialchars($alternatif[$idA]) ?></th>
                <?php foreach ($row as $val): ?>
                <td><?= number_format($val, 3) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Flow Tables -->
    <h5 class="mt-4">Leaving, Entering, dan Net Flow</h5>
    <table class="table table-bordered w-50 text-center">
        <thead class="table-primary">
            <tr>
                <th>Alternatif</th>
                <th>Leaving Flow</th>
                <th>Entering Flow</th>
                <th>Net Flow</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($netflow as $idA => $val): ?>
            <tr>
                <td><?= htmlspecialchars($alternatif[$idA]) ?></td>
                <td><?= number_format($leaving[$idA], 3) ?></td>
                <td><?= number_format($entering[$idA], 3) ?></td>
                <td class="fw-bold"><?= number_format($netflow[$idA], 3) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Ranking -->
    <h5 class="mt-4">Rangking Siswa Terbaik</h5>
    <ol>
        <?php foreach ($netflow as $idA => $val): ?>
        <li><?= htmlspecialchars($alternatif[$idA]) ?> <span class="text-muted">(Net Flow: <?= number_format($val, 3) ?>)</span></li>
        <?php endforeach; ?>
    </ol>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

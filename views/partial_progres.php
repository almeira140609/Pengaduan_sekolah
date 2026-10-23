<?php

// =========================================================
// PARTIAL: STEPPER STATUS + RIWAYAT PROGRES
//
// Variabel yang harus tersedia sebelum di-include:
//   $status_stepper        status sekarang (menunggu/dikerjakan/selesai)
//   $riwayat_stepper       array hasil Progres::ambilRiwayat()
//   $tanggal_awal_stepper  tanggal aspirasi dibuat (untuk langkah "Menunggu")
// =========================================================

require_once __DIR__ . "/../models/m_progres.php";

$status_stepper = Progres::normalisasi($status_stepper ?? 'menunggu');

$riwayat_stepper = $riwayat_stepper ?? [];

$langkah_stepper = [
    'menunggu' => 'Menunggu',
    'dikerjakan' => 'Dikerjakan',
    'selesai' => 'Selesai'
];

$posisi_stepper = array_search(
    $status_stepper,
    Progres::URUTAN_STATUS,
    true
);

// Waktu tiap langkah tercapai
$waktu_stepper = [];

if (!empty($tanggal_awal_stepper)) {
    $waktu_stepper['menunggu'] = $tanggal_awal_stepper;
}

foreach ($riwayat_stepper as $r) {

    if (!isset($waktu_stepper[$r['status_progres']])) {
        $waktu_stepper[$r['status_progres']] = $r['tanggal_progres'];
    }
}

?>
<link rel="stylesheet" href="../assets/partial_progres.css">



<!-- STEPPER STATUS -->

<div class="pg-stepper">

    <?php foreach ($langkah_stepper as $kode => $label): ?>

        <?php

        $indeks = array_search($kode, Progres::URUTAN_STATUS, true);

        $kelas = '';

        if ($indeks < $posisi_stepper) {
            $kelas = 'pg-done';
        } elseif ($indeks == $posisi_stepper) {
            $kelas = 'pg-now';

            if ($kode == 'selesai') {
                $kelas .= ' pg-final';
            }
        }

        ?>

        <div class="pg-step <?= $kelas; ?>">

            <div class="pg-dot">
                <?= ($indeks < $posisi_stepper || ($indeks == $posisi_stepper && $kode == 'selesai'))
                    ? '✓'
                    : ($indeks + 1); ?>
            </div>

            <div class="pg-label">
                <?= $label; ?>
            </div>

            <div class="pg-waktu">
                <?= (!empty($waktu_stepper[$kode]) && $indeks <= $posisi_stepper)
                    ? date('d-m-Y H:i', strtotime($waktu_stepper[$kode]))
                    : '&nbsp;'; ?>
            </div>

        </div>

    <?php endforeach; ?>

</div>


<!-- RIWAYAT PROGRES -->

<div class="pg-riwayat-judul">
    Riwayat Progres
</div>

<?php if (!empty($riwayat_stepper)): ?>

    <div class="pg-riwayat">

        <?php foreach ($riwayat_stepper as $r): ?>

            <div class="pg-item">

                <div class="pg-item-atas">

                    <span class="pg-badge <?= $r['status_progres']; ?>">
                        <?= ucfirst($r['status_progres']); ?>
                    </span>

                    <span class="pg-meta">
                        <?= htmlspecialchars($r['nama_admin'] ?? 'Admin'); ?>
                        &middot;
                        <?= !empty($r['tanggal_progres'])
                            ? date('d-m-Y H:i', strtotime($r['tanggal_progres']))
                            : '-'; ?>
                    </span>

                </div>

                <div class="pg-catatan">
                    <?= !empty($r['keterangan'])
                        ? htmlspecialchars($r['keterangan'])
                        : 'Belum ada keterangan progres.'; ?>
                </div>

                <?php if (!empty($r['foto'])): ?>

                    <div class="pg-foto">

                        <p>Foto Bukti</p>

                        <img
                            src="../uploads/<?= htmlspecialchars($r['foto']); ?>"
                            alt="Foto Bukti Progres"
                        >

                    </div>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="pg-kosong">
        Belum ada progres. Laporan masih menunggu diproses oleh admin.
    </div>

<?php endif; ?>
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

<style>

    .pg-stepper {
        display: flex;
        align-items: flex-start;
        margin: 4px 0 26px;
    }

    .pg-step {
        flex: 1;
        position: relative;
        text-align: center;
    }

    .pg-step::before {
        content: "";
        position: absolute;
        top: 15px;
        left: -50%;
        width: 100%;
        height: 3px;
        background: #e3e6ee;
        z-index: 0;
    }

    .pg-step:first-child::before {
        display: none;
    }

    .pg-step.pg-done::before,
    .pg-step.pg-now::before {
        background: #7567d9;
    }

    .pg-dot {
        position: relative;
        z-index: 1;
        width: 32px;
        height: 32px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #e3e6ee;
        color: #98a1af;
        font-size: 13px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pg-step.pg-done .pg-dot {
        background: #7567d9;
        color: white;
    }

    .pg-step.pg-now .pg-dot {
        background: #7567d9;
        color: white;
        box-shadow: 0 0 0 5px rgba(117, 103, 217, 0.18);
    }

    .pg-step.pg-now.pg-final .pg-dot {
        background: #18a558;
        box-shadow: 0 0 0 5px rgba(24, 165, 88, 0.18);
    }

    .pg-label {
        font-size: 12px;
        font-weight: bold;
        color: #98a1af;
    }

    .pg-step.pg-done .pg-label,
    .pg-step.pg-now .pg-label {
        color: #303b4e;
    }

    .pg-waktu {
        margin-top: 3px;
        font-size: 10px;
        color: #9aa3b2;
    }


    .pg-riwayat-judul {
        margin: 6px 0 12px;
        font-size: 13px;
        font-weight: bold;
        color: #303b4e;
    }

    .pg-riwayat {
        border-left: 3px solid #e8e4ff;
        margin-left: 6px;
        padding-left: 18px;
    }

    .pg-item {
        position: relative;
        margin-bottom: 14px;
        padding: 14px 16px;
        background: #faf9ff;
        border: 1px solid #e8e4ff;
        border-radius: 8px;
    }

    .pg-item:last-child {
        margin-bottom: 0;
    }

    .pg-item::before {
        content: "";
        position: absolute;
        left: -27px;
        top: 18px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #7567d9;
        border: 2px solid white;
    }

    .pg-item-atas {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 9px;
    }

    .pg-badge {
        display: inline-block;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .pg-badge.menunggu {
        background: #fff3cd;
        color: #856404;
    }

    .pg-badge.dikerjakan {
        background: #dcecff;
        color: #245b9c;
    }

    .pg-badge.selesai {
        background: #d9f5e5;
        color: #18733c;
    }

    .pg-meta {
        font-size: 11px;
        color: #9aa3b2;
    }

    .pg-catatan {
        font-size: 13px;
        color: #596273;
        line-height: 1.6;
        white-space: pre-line;
    }

    .pg-foto {
        margin-top: 12px;
    }

    .pg-foto p {
        margin-bottom: 6px;
        font-size: 11px;
        color: #98a1af;
    }

    .pg-foto img {
        display: block;
        width: 100%;
        max-width: 320px;
        max-height: 230px;
        object-fit: contain;
        background: white;
        border: 1px solid #e5e8ee;
        border-radius: 8px;
    }

    .pg-kosong {
        padding: 16px;
        text-align: center;
        font-size: 12px;
        color: #8992a2;
        background: #fafbfc;
        border-radius: 8px;
    }

</style>


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
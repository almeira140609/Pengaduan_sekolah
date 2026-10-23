
<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_kategori.php";

$kategori = new Kategori();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$data_kategori = $kategori->tampil($search);

$nama_admin = isset($_SESSION['nama_admin'])
    ? $_SESSION['nama_admin']
    : 'Admin';

// =========================
// NOTIFIKASI
// =========================

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';

$daftar_pesan = [
    'tambah' => [
        'berhasil',
        'Kategori berhasil ditambahkan.'
    ],
    'edit' => [
        'berhasil',
        'Kategori berhasil diperbarui.'
    ],
    'hapus' => [
        'berhasil',
        'Kategori berhasil dihapus.'
    ],
    'gagal_tambah' => [
        'gagal',
        'Kategori gagal ditambahkan.'
    ],
    'gagal_edit' => [
        'gagal',
        'Kategori gagal diperbarui.'
    ],
    'gagal_hapus' => [
        'gagal',
        'Kategori tidak dapat dihapus karena masih digunakan pada data aspirasi.'
    ]
];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kategori - Pengaduan Sekolah</title>

    <link rel="stylesheet" href="../assets/v_kategori.css">

</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">
            <h2>Pengaduan<br>Sekolah</h2>
            <p>Sistem Sarana &amp; Prasarana</p>
        </div>

        <div class="menu">

            <div class="menu-title">Menu Admin</div>

            <a href="dashboard_admin.php">
                <span class="icon">⌂</span>
                <span>Dashboard</span>
            </a>

            <a href="v_siswa.php">
                <span class="icon">♙</span>
                <span>Data Siswa</span>
            </a>

            <a href="v_kategori.php" class="active">
                <span class="icon">▦</span>
                <span>Kategori</span>
            </a>

            <a href="v_aspirasi.php">
                <span class="icon">▤</span>
                <span>Aspirasi</span>
            </a>

            <a href="v_histori.php">
                <span class="icon">◷</span>
                <span>Histori</span>
            </a>

        </div>

        <div class="logout">
            <a href="logout.php">
                <span class="icon">←</span>
                <span>Logout</span>
            </a>
        </div>

    </div>

    <!-- MAIN CONTENT -->

    <div class="main-content">

        <!-- TOPBAR -->

        <div class="topbar">

            <div class="topbar-text">
                Sistem Pengaduan Sarana &amp; Prasarana Sekolah
            </div>

            <div class="admin-info">

                <div class="admin-avatar">
                    <?= htmlspecialchars(strtoupper(substr($nama_admin, 0, 1))); ?>
                </div>

                <div>
                    <div class="admin-name">
                        <?= htmlspecialchars($nama_admin); ?>
                    </div>

                    <div class="admin-role">
                        Admin
                    </div>
                </div>

            </div>

        </div>

        <!-- CONTENT -->

        <div class="content">

            <!-- NOTIFIKASI -->

            <?php if (isset($daftar_pesan[$pesan])): ?>

                <div class="notifikasi <?= $daftar_pesan[$pesan][0]; ?>">
                    <?= htmlspecialchars($daftar_pesan[$pesan][1]); ?>
                </div>

            <?php endif; ?>

            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="page-title">
                    <h1>Data Kategori</h1>

                    <p>
                        Kelola kategori sarana dan prasarana sekolah.
                    </p>
                </div>

                <a href="tambah_kategori.php" class="btn-tambah">
                    ＋ Tambah Kategori
                </a>

            </div>

            <!-- TABLE CARD -->

            <div class="table-card">

                <div class="table-header">

                    <div class="table-title">

                        <div>
                            <h3>Daftar Kategori</h3>

                            <p>
                                Kategori digunakan untuk mengelompokkan pengaduan siswa.
                            </p>
                        </div>

                        <form method="GET" action="v_kategori.php" class="search-form">

                            <input
                                type="text"
                                name="search"
                                placeholder="Cari kategori..."
                                value="<?= htmlspecialchars($search); ?>"
                            >

                            <button type="submit">
                                Cari
                            </button>

                            <?php if ($search != ''): ?>

                                <a href="v_kategori.php" class="btn-reset">
                                    Reset
                                </a>

                            <?php endif; ?>

                        </form>

                    </div>

                </div>

                <!-- TABLE -->

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th width="70">No</th>
                                <th>Nama Kategori</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            if ($data_kategori && mysqli_num_rows($data_kategori) > 0):

                                while ($row = mysqli_fetch_assoc($data_kategori)):
                            ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kategori']); ?>
                                    </td>

                                    <td>

                                        <a
                                            href="edit_kategori.php?id=<?= (int) $row['id_kategori']; ?>"
                                            class="btn-edit"
                                            title="Edit Kategori"
                                            aria-label="Edit Kategori"
                                        >✏️</a>

                                        <a
                                            href="../controllers/c_kategori.php?hapus=<?= (int) $row['id_kategori']; ?>"
                                            class="btn-hapus"
                                            title="Hapus Kategori"
                                            aria-label="Hapus Kategori"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?');"
                                        >🗑️</a>

                                    </td>

                                </tr>

                            <?php
                                endwhile;

                            else:
                            ?>

                                <tr>
                                    <td colspan="3" class="empty-data">

                                        <div class="empty-icon">🔍</div>

                                        <strong>
                                            Data kategori tidak ditemukan
                                        </strong>

                                        <p>
                                            Coba gunakan kata kunci pencarian yang berbeda.
                                        </p>

                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>
</html>

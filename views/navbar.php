<?php

$halaman = basename($_SERVER['PHP_SELF']);

?>

<style>

    .sidebar {
        width: 230px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background-color: #2c3e50;
        color: white;
    }

    .logo {
        padding: 25px 20px;
        font-size: 20px;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .menu {
        padding: 20px 10px;
    }

    .menu a {
        display: block;
        padding: 13px 15px;
        margin-bottom: 5px;
        color: #ecf0f1;
        text-decoration: none;
        border-radius: 6px;
        font-size: 15px;
    }

    .menu a:hover {
        background-color: #34495e;
    }

    .menu a.active {
        background-color: #3498db;
        color: white;
    }

    .logout {
        position: absolute;
        bottom: 20px;
        width: 100%;
    }

    .logout a {
        display: block;
        margin: 0 10px;
        padding: 13px 15px;
        color: #ecf0f1;
        text-decoration: none;
        border-radius: 6px;
        font-size: 15px;
    }

    .logout a:hover {
        background-color: #34495e;
    }

</style>


<div class="sidebar">

    <div class="logo">
        Pengaduan Sekolah
    </div>


    <div class="menu">

        <a href="dashboard_admin.php"
           class="<?= ($halaman == 'dashboard_admin.php') ? 'active' : ''; ?>">
            🏠 Dashboard
        </a>


        <a href="v_siswa.php"
           class="<?= ($halaman == 'v_siswa.php') ? 'active' : ''; ?>">
            👨‍🎓 Data Siswa
        </a>


        <a href="v_kategori.php"
           class="<?= ($halaman == 'v_kategori.php') ? 'active' : ''; ?>">
            📂 Kategori
        </a>


        <a href="v_aspirasi.php"
           class="<?= ($halaman == 'v_aspirasi.php') ? 'active' : ''; ?>">
            📋 Aspirasi
        </a>

               <a href="v_histori.php">

            <span class="icon">▤</span>

            <span>
                Histori
            </span>


    </div>


    <div class="logout">

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>
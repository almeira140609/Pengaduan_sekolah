 <?php

$current_page = basename($_SERVER['PHP_SELF']);

?>

<style>

    .sidebar-siswa {
        position: fixed;
        left: 0;
        top: 0;

        width: 230px;
        height: 100vh;

        background-color: #20283a;

        padding: 25px 15px;

        color: white;

        z-index: 1000;
    }

    /* LOGO */

    .sidebar-logo {
        display: flex;
        align-items: center;

        gap: 12px;

        padding: 0 10px 30px;

        border-bottom: 1px solid rgba(255,255,255,0.08);

        margin-bottom: 25px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;

        border-radius: 10px;

        background-color: #7567d9;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 20px;
    }

    .logo-text strong {
        display: block;

        font-size: 15px;

        color: white;
    }

    .logo-text span {
        display: block;

        font-size: 11px;

        color: #9ca3af;

        margin-top: 3px;
    }

    /* MENU */

    .menu-title {
        font-size: 10px;

        color: #7f899d;

        text-transform: uppercase;

        letter-spacing: 1px;

        padding: 0 12px;

        margin-bottom: 10px;
    }

    .menu-item {
        display: flex;

        align-items: center;

        gap: 12px;

        width: 100%;

        padding: 12px 13px;

        margin-bottom: 5px;

        border-radius: 8px;

        color: #aeb6c5;

        text-decoration: none;

        font-size: 13px;

        transition: 0.2s;
    }

    .menu-item:hover {
        background-color: rgba(255,255,255,0.06);

        color: white;
    }

    .menu-item.active {
        background-color: #7567d9;

        color: white;
    }

    .menu-icon {
        width: 20px;

        text-align: center;

        font-size: 16px;
    }

    /* LOGOUT */

    .sidebar-bottom {
        position: absolute;

        bottom: 20px;

        left: 15px;

        right: 15px;

        padding-top: 15px;

        border-top: 1px solid rgba(255,255,255,0.08);
    }

    .logout {
        color: #e5a3a3;
    }

    .logout:hover {
        background-color: rgba(231, 76, 60, 0.12);

        color: #ffb0b0;
    }

</style>


<div class="sidebar-siswa">

    <!-- LOGO -->

    <div class="sidebar-logo">

        <div class="logo-icon">
            📢
        </div>

        <div class="logo-text">

            <strong>Pengaduan Sekolah</strong>

            <span>Sistem Aspirasi Siswa</span>

        </div>

    </div>


    <!-- MENU -->

    <div class="menu-title">
        Menu Utama
    </div>


    <a
        href="dashboard_siswa.php"
        class="menu-item <?= $current_page == 'dashboard_siswa.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            🏠
        </span>

        <span>
            Dashboard
        </span>

    </a>


    <a
        href="v_aspirasi_siswa.php"
        class="menu-item <?= $current_page == 'v_aspirasi_siswa.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            📝
        </span>

        <span>
            Kirim Aspirasi
        </span>

    </a>


    <a
        href="histori_aspirasi.php"
        class="menu-item <?= $current_page == 'histori_aspirasi.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            📋
        </span>

        <span>
            Histori Aspirasi
        </span>

    </a>


    <a
        href="status_aspirasi.php"
        class="menu-item <?= $current_page == 'status_aspirasi.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            📊
        </span>

        <span>
            Status Aspirasi
        </span>

    </a>


    <a
        href="progres_aspirasi.php"
        class="menu-item <?= $current_page == 'progres_aspirasi.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            🔧
        </span>

        <span>
            Progres Perbaikan
        </span>

    </a>


    <a
        href="feedback_aspirasi.php"
        class="menu-item <?= $current_page == 'feedback_aspirasi.php' ? 'active' : ''; ?>"
    >

        <span class="menu-icon">
            💬
        </span>

        <span>
            Umpan Balik
        </span>

    </a>


    <!-- BOTTOM -->

    <div class="sidebar-bottom">

        <a
            href="logout.php"
            class="menu-item logout"
            onclick="return confirm('Yakin ingin keluar?')"
        >

            <span class="menu-icon">
                🚪
            </span>

            <span>
                Keluar
            </span>

        </a>

    </div>

</div>
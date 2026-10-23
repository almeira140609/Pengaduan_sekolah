<?php

session_start();


// =========================
// JIKA SUDAH LOGIN
// =========================

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: dashboard_admin.php");
        exit;
    }

    if ($_SESSION['role'] == 'siswa') {
        header("Location: dashboard_siswa.php");
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Pengaduan Sekolah</title>


   <link rel="stylesheet" href="../assets/login.css">


</head>


<body>


<div class="login-container">


    <div class="login-card">


        <div class="logo">

            <div class="logo-icon">
                PS
            </div>

            <h1>
                Pengaduan Sekolah
            </h1>

            <p>
                Sistem Sarana & Prasarana
            </p>

        </div>


        <?php if (isset($_GET['error'])) { ?>

            <div class="error">

                Username atau password salah.

            </div>

        <?php } ?>

    
 <?php if (isset($_GET['logout'])) { ?>

    <div class="success">

        ✓ Kamu berhasil logout.

    </div>

<?php } ?>

        <form
            action="../controllers/c_login.php"
            method="POST"
        >


            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    placeholder="Masukkan username"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan password"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn-login"
            >
                Login
            </button>


        </form>


        <div class="footer">

            Pengaduan Sarana & Prasarana Sekolah

        </div>


    </div>

</div>


</body>

</html>
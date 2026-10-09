<?php

session_start();


// Hapus semua session
session_unset();

session_destroy();


// Kembali ke login dengan tanda logout berhasil
header("Location: login.php?logout=1");

exit;

?>
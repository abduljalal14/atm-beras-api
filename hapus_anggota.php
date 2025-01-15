<?php
$conn = new mysqli('localhost', 'root', '', 'atm_beras');
if ($conn->connect_error) {
    die('Database connection failed');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus anggota berdasarkan ID
    $conn->query("DELETE FROM anggota WHERE id = $id");

    // Redirect kembali ke halaman anggota setelah penghapusan
    header('Location: index.php?page=anggota');
}
?>

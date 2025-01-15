<?php 
$conn = new mysqli('localhost', 'root', '', 'atm_beras');
if ($conn->connect_error) {
    die('Database connection failed');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Beras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ATM Beras</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=anggota">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=log">Log</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php
        $page = $_GET['page'] ?? 'anggota';
        if ($page === 'anggota') {
            $result = $conn->query("SELECT * FROM anggota");
            echo '<h1>Daftar Anggota</h1>';
            echo '<a href="tambah_anggota.php" class="btn btn-primary mb-3">Tambah Anggota</a>';
            echo '<table class="table">';
            echo '<thead><tr><th>#</th><th>Nama</th><th>Kode Kartu</th><th>Saldo Beras</th><th>Aksi</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['card_code']}</td>
            <td class='text-end' >{$row['saldo_beras']} gram</td>
            <td>
                
                <a href='tambah_saldo.php?id={$row['id']}' class='btn btn-success btn-sm'>Tambah Saldo</a>
                <a href='edit_anggota.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                <a href='hapus_anggota.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(&quot;Yakin ingin menghapus anggota ini?&quot;)'>Hapus</a>
            </td>
        </tr>";

            }
            echo '</tbody></table>';
        } elseif ($page === 'log') {
            $result = $conn->query("SELECT * FROM log ORDER BY created_at DESC");
            echo '<h1>Log Transaksi</h1>';
            echo '<table class="table">';
            echo '<thead><tr><th>#</th><th>Nama</th><th>Mutasi</th><th>Waktu</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['id']}</td><td>{$row['nama']}</td><td>{$row['mutation']} gram</td><td>{$row['created_at']}</td></tr>";
            }
            echo '</tbody></table>';
        }
        ?>
    </div>
</body>
</html>

<?php
$conn = new mysqli('localhost', 'root', '', 'atm_beras');
if ($conn->connect_error) {
    die('Database connection failed');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data anggota berdasarkan ID
    $result = $conn->query("SELECT * FROM anggota WHERE id = $id");
    if ($result->num_rows > 0) {
        $anggota = $result->fetch_assoc();

        // Proses tambah saldo jika form disubmit
        if (isset($_POST['tambah_saldo'])) {
            $saldo_tambah = $_POST['saldo'];

            // Update saldo
            $conn->query("UPDATE anggota SET saldo_beras = saldo_beras + $saldo_tambah WHERE id = $id");

            // Menambahkan log mutasi
            $nama = "Top Up - " . $anggota['name'];
            $mutation = $saldo_tambah;

            // Masukkan log ke dalam tabel log
            $conn->query("INSERT INTO log (nama, mutation) VALUES ('$nama', '$mutation')");

            // Redirect kembali ke halaman anggota setelah update saldo
            header('Location: index.php?page=anggota');
            exit();  // Pastikan script berhenti setelah redirect
        }
    } else {
        die("Anggota tidak ditemukan");  // Menampilkan pesan jika anggota tidak ditemukan
    }
} else {
    die("ID anggota tidak ditemukan");  // Menampilkan pesan jika parameter ID tidak ada
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Saldo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Tambah Saldo untuk <?php echo htmlspecialchars($anggota['name']); ?></h1>
        <form method="POST">
            <div class="mb-3">
                <label for="saldo" class="form-label">Jumlah Saldo yang Ditambahkan</label>
                <input type="number" name="saldo" id="saldo" class="form-control" required>
            </div>
            <button type="submit" name="tambah_saldo" class="btn btn-success">Tambah Saldo</button>
        </form>
    </div>
</body>
</html>

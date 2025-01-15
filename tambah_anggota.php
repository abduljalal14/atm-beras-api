<?php
// Mulai sesi untuk mengambil data yang disimpan sementara
session_start();

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'atm_beras');

// Menangani form POST untuk menambahkan anggota
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $card_code = $_POST['card_code'];
    $saldo_beras = $_POST['saldo_beras'];

    // Cek apakah kode kartu sudah terdaftar
    $result = $conn->query("SELECT * FROM anggota WHERE card_code = '$card_code'");
    if ($result->num_rows > 0) {
        // Jika kode kartu sudah ada, simpan error ke sesi dan tampilkan pesan error
        $_SESSION['error_message'] = 'Kode kartu sudah terdaftar!';
    } else {
        // Menyimpan data anggota ke dalam database jika kode kartu belum terdaftar
        $conn->query("INSERT INTO anggota (name, card_code, saldo_beras) VALUES ('$name', '$card_code', '$saldo_beras')");

        // Redirect setelah berhasil menambah anggota
        header('Location: index.php?page=anggota');
        exit();
    }
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Tambah Anggota</h1>

        <?php
        // Menampilkan pesan error jika ada
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);  // Menghapus pesan setelah ditampilkan
        }
        ?>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="card_code" class="form-label">Kode Kartu</label>
                <input type="text" class="form-control" id="card_code" name="card_code" 
                value="<?php echo isset($_SESSION['temp_card_code']) ? $_SESSION['temp_card_code'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="saldo_beras" class="form-label">Saldo Beras</label>
                <input type="number" class="form-control" id="saldo_beras" name="saldo_beras" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</body>
</html>

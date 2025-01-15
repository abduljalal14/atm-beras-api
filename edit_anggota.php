<?php
$conn = new mysqli('localhost', 'root', '', 'atm_beras');
if ($conn->connect_error) {
    die('Database connection failed');
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $card_code = $_POST['card_code'];

    // Update data anggota tanpa saldo_beras
    $conn->query("UPDATE anggota SET name = '$name', card_code = '$card_code' WHERE id = $id");

    // Redirect kembali ke halaman anggota
    header('Location: index.php?page=anggota');
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM anggota WHERE id = $id");
$anggota = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Anggota</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $anggota['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="card_code" class="form-label">Kode Kartu</label>
                <input type="text" name="card_code" id="card_code" class="form-control" value="<?php echo $anggota['card_code']; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Anggota</button>
        </form>
    </div>
</body>
</html>

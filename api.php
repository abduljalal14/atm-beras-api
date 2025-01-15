<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'atm_beras'); // Sesuaikan dengan kredensial Anda

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ''; // Ambil parameter 'action' dari URL

// Mulai sesi untuk menyimpan kode kartu sementara
session_start();

// Fungsi untuk menangani operasi CRUD
switch ($method) {
    case 'GET':
        if ($action === 'anggota') {
            getAllAnggota($conn);
        } else {
            handleGetRequest($conn, $action);
        }
        break;
    case 'POST':
        handlePostRequest($conn, $action);
        break;
    case 'PUT':
        handlePutRequest($conn, $action);
        break;
    case 'DELETE':
        handleDeleteRequest($conn, $action);
        break;
    default:
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn->close();

function getAllAnggota($conn)
{
    $result = $conn->query("SELECT * FROM anggota");

    if ($result->num_rows > 0) {
        $anggota = [];
        while ($row = $result->fetch_assoc()) {
            $anggota[] = $row;
        }
        echo json_encode($anggota);
    } else {
        echo json_encode(['status' => 'tidak_ada_anggota']);
    }
}
function handleGetRequest($conn, $action)
{
    if ($action === 'getSaldo') {
        $cardCode = $_GET['id_kartu'] ?? '';
        if (!$cardCode) {
            echo json_encode(['error' => 'Missing card code']);
            return;
        }

        $result = $conn->query("SELECT saldo_beras FROM anggota WHERE card_code = '$cardCode'");
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(['saldo_beras' => $data['saldo_beras']]);
        } else {
            $_SESSION['temp_card_code'] = $cardCode;
            echo json_encode(['status' => 'kartu_tidak_terdaftar']);
        }
    }
}

function handlePostRequest($conn, $action)
{
    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'checkCard') {
        $cardCode = $input['id_kartu'];
        $result = $conn->query("SELECT saldo_beras FROM anggota WHERE card_code = '$cardCode'");
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(['saldo_beras' => $data['saldo_beras']]);
        } else {
            $_SESSION['temp_card_code'] = $cardCode;
            echo json_encode(['status' => 'kartu_tidak_terdaftar']);
        }
    }

    if ($action === 'deductBalance') {
        $cardCode = $input['id_kartu'];
        $berat = $input['berat'];

        $result = $conn->query("SELECT * FROM anggota WHERE card_code = '$cardCode'");
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if ($data['saldo_beras'] >= $berat) {
                $conn->query("UPDATE anggota SET saldo_beras = saldo_beras - $berat WHERE card_code = '$cardCode'");
                $conn->query("INSERT INTO log (nama, mutation) VALUES ('{$data['name']}', -$berat)");
                echo json_encode(['status' => 'berhasil']);
            } else {
                echo json_encode(['status' => 'saldo_tidak_cukup']);
            }
        } else {
            $_SESSION['temp_card_code'] = $cardCode;
            echo json_encode(['status' => 'kartu_tidak_terdaftar']);
        }
    }
}

function handlePutRequest($conn, $action)
{
    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'updateSaldo') {
        $cardCode = $input['id_kartu'];
        $berat = $input['berat'];

        $result = $conn->query("SELECT * FROM anggota WHERE card_code = '$cardCode'");
        if ($result->num_rows > 0) {
            $conn->query("UPDATE anggota SET saldo_beras = saldo_beras + $berat WHERE card_code = '$cardCode'");
            echo json_encode(['status' => 'saldo_berhasil_diupdate']);
        } else {
            $_SESSION['temp_card_code'] = $cardCode;
            echo json_encode(['status' => 'kartu_tidak_terdaftar']);
        }
    }
}

function handleDeleteRequest($conn, $action)
{
    if ($action === 'deleteCard') {
        $cardCode = $_GET['id_kartu'] ?? '';
        if (!$cardCode) {
            echo json_encode(['error' => 'Missing card code']);
            return;
        }

        $result = $conn->query("DELETE FROM anggota WHERE card_code = '$cardCode'");
        if ($result) {
            echo json_encode(['status' => 'kartu_berhasil_dihapus']);
        } else {
            echo json_encode(['status' => 'gagal_menghapus_kartu']);
        }
    }
}
?>

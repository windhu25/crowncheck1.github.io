<?php
include "service/database.php";

$message = ""; 

if (isset($_POST['input'])) {
    $crown_id = isset($_POST["crown_id"]) ? trim($_POST["crown_id"]) : null;
    $nama_crown = isset($_POST["nama_crown"]) ? trim($_POST["nama_crown"]) : null;
    $tanggal = isset($_POST["tanggal"]) ? trim($_POST["tanggal"]) : null;

    if ($crown_id || $nama_crown) {
        // Menyusun query sesuai dengan parameter yang diisi
        if ($crown_id && $nama_crown) {
            $sql = "SELECT Stok FROM crown WHERE crown_id = ? OR Nama_crown = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ss", $crown_id, $nama_crown);
        } elseif ($crown_id) {
            $sql = "SELECT Stok FROM crown WHERE crown_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $crown_id);
        } else {
            $sql = "SELECT Stok FROM crown WHERE Nama_crown = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $nama_crown);
        }

        $stmt->execute();
        $stmt->bind_result($stok);
        $stmt->fetch();
        $stmt->close();

        if ($stok === null) {
            $message = "<div class='message error'>Crown ID atau Nama Crown tidak ditemukan.</div>";
        } else {
            // Menentukan query transaksi berdasarkan parameter yang diberikan
            if ($crown_id) {
                $sql_transaksi = "
                    SELECT COUNT(*) 
                    FROM transaksi 
                    WHERE crown_id = ? 
                    AND (tanggal_pinjam <= ? AND tanggal_kembali >= ?)
                ";
                $stmt_transaksi = $db->prepare($sql_transaksi);
                $stmt_transaksi->bind_param("sss", $crown_id, $tanggal, $tanggal);
            } else {
                $sql_transaksi = "
                    SELECT COUNT(*) 
                    FROM transaksi 
                    JOIN crown ON transaksi.crown_id = crown.crown_id
                    WHERE Nama_crown = ? 
                    AND (tanggal_pinjam <= ? AND tanggal_kembali >= ?)
                ";
                $stmt_transaksi = $db->prepare($sql_transaksi);
                $stmt_transaksi->bind_param("sss", $nama_crown, $tanggal, $tanggal);
            }

            $stmt_transaksi->execute();
            $stmt_transaksi->bind_result($count);
            $stmt_transaksi->fetch();
            $stmt_transaksi->close();

            if ($count > 0) {
                $message = "<div class='message error'>Barang tidak tersedia pada tanggal ini.</div>";
            } else {
                $message = "<div class='message success'>Barang tersedia pada tanggal ini. Stok: $stok</div>";
            }
        }
    } else {
        $message = "<div class='message error'>Crown ID atau Nama Crown harus diisi.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ketersediaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="date"],
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h3>Cek Ketersediaan</h3>
    <form action="" method="POST">
        <input type="text" placeholder="Crown ID" name="crown_id" />
        <input type="text" placeholder="Nama Crown" name="nama_crown" />
        <input type="date" placeholder="Tanggal" name="tanggal" />
        <button type="submit" name="input">Cari</button>
        <a href="dashboard.php" class="link">Kembali</a>
    </form>

    <?php
    echo $message;
    ?>
</body>
</html>

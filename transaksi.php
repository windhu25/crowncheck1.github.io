<?php
include "service/database.php";

// Tangani input transaksi baru
if (isset($_POST['submit'])) {
    $nama_crown = $_POST['nama_crown'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    // Cek apakah nama_crown valid
    $sql_get_crown_id = "SELECT crown_id FROM crown WHERE nama_crown = ?";
    $stmt_get_crown_id = $db->prepare($sql_get_crown_id);
    $stmt_get_crown_id->bind_param("s", $nama_crown);
    $stmt_get_crown_id->execute();
    $stmt_get_crown_id->bind_result($crown_id);
    $stmt_get_crown_id->fetch();
    $stmt_get_crown_id->close();

    if ($crown_id) {
        // Cek apakah nama_pelanggan valid
        $sql_get_pelanggan_id = "SELECT pelanggan_id FROM pelanggan WHERE nama = ?";
        $stmt_get_pelanggan_id = $db->prepare($sql_get_pelanggan_id);
        $stmt_get_pelanggan_id->bind_param("s", $nama_pelanggan);
        $stmt_get_pelanggan_id->execute();
        $stmt_get_pelanggan_id->bind_result($pelanggan_id);
        $stmt_get_pelanggan_id->fetch();
        $stmt_get_pelanggan_id->close();

        if ($pelanggan_id) {
            // Cek stok crown
            $sql_check_stok = "SELECT stok FROM crown WHERE crown_id = ?";
            $stmt_check_stok = $db->prepare($sql_check_stok);
            $stmt_check_stok->bind_param("s", $crown_id);
            $stmt_check_stok->execute();
            $stmt_check_stok->bind_result($stok);
            $stmt_check_stok->fetch();
            $stmt_check_stok->close();

            // Cek apakah ada transaksi aktif untuk crown tersebut
            $sql_check_active_transaksi = "SELECT tanggal_kembali FROM transaksi WHERE crown_id = ? AND status = 0";
            $stmt_check_active_transaksi = $db->prepare($sql_check_active_transaksi);
            $stmt_check_active_transaksi->bind_param("s", $crown_id);
            $stmt_check_active_transaksi->execute();
            $stmt_check_active_transaksi->bind_result($active_tanggal_kembali);
            $stmt_check_active_transaksi->fetch();
            $stmt_check_active_transaksi->close();

            if ($stok > 0 || ($active_tanggal_kembali && $tanggal_kembali > $active_tanggal_kembali)) {
                // Masukkan transaksi baru
                $sql_insert_transaksi = "INSERT INTO transaksi (crown_id, pelanggan_id, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, 0)";
                $stmt_insert_transaksi = $db->prepare($sql_insert_transaksi);
                $stmt_insert_transaksi->bind_param("ssss", $crown_id, $pelanggan_id, $tanggal_pinjam, $tanggal_kembali);

                if ($stmt_insert_transaksi->execute()) {
                    // Kurangi stok setelah transaksi berhasil
                    if ($stok > 0) {
                        $sql_update_stok = "UPDATE crown SET stok = stok - 1 WHERE crown_id = ?";
                        $stmt_update_stok = $db->prepare($sql_update_stok);
                        $stmt_update_stok->bind_param("s", $crown_id);
                        $stmt_update_stok->execute();
                        $stmt_update_stok->close();
                    }
                    $message = "<div class='message success'>Transaksi berhasil ditambahkan.</div>";
                } else {
                    $message = "<div class='message error'>Error: " . $stmt_insert_transaksi->error . "</div>";
                }
                $stmt_insert_transaksi->close();
            } else {
                $message = "<div class='message error'>Stok tidak cukup dan tanggal kembali belum lewat.</div>";
            }
        } else {
            $message = "<div class='message error'>Nama Pelanggan tidak valid.</div>";
        }
    } else {
        $message = "<div class='message error'>Nama Crown tidak valid.</div>";
    }
}

// Fetch crown and pelanggan for dropdown
$crown_options = [];
$pelanggan_options = [];

// Fetch crown options
$sql_crown_options = "SELECT nama_crown FROM crown";
$result_crown_options = $db->query($sql_crown_options);
if ($result_crown_options) {
    while ($row = $result_crown_options->fetch_assoc()) {
        $crown_options[] = $row['nama_crown'];
    }
}

// Fetch pelanggan options
$sql_pelanggan_options = "SELECT nama FROM pelanggan";
$result_pelanggan_options = $db->query($sql_pelanggan_options);
if ($result_pelanggan_options) {
    while ($row = $result_pelanggan_options->fetch_assoc()) {
        $pelanggan_options[] = $row['nama'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Biru terang */
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #0277bd; /* Biru tua */
            color: white;
            text-align: center;
            padding: 1rem;
            font-size: 1.5rem;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h3 {
            color: #0277bd; /* Biru tua */
        }
        form {
            display: flex;
            flex-direction: column;
        }
        select, input[type="date"], button {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #0277bd; /* Biru tua */
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #01579b; /* Biru yang lebih tua saat hover */
        }
        .message {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .message.success {
            background-color: #b2ebf2; /* Biru terang untuk pesan sukses */
            color: #004d40;
        }
        .message.error {
            background-color: #ffcdd2; /* Merah muda untuk pesan error */
            color: #b71c1c;
        }
        .link {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            color: #0277bd; /* Biru tua */
            text-decoration: none;
            border: 1px solid #0277bd;
            border-radius: 4px;
        }
        .link:hover {
            background-color: #0277bd;
            color: white;
        }
    </style>
</head>
<body>
    <header>Tambah Transaksi</header>

    <div class="container">
        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <h3>Input Transaksi Baru</h3>
        <form action="transaksi.php" method="POST">
            <select name="nama_crown" required>
                <option value="">Pilih Crown</option>
                <?php foreach ($crown_options as $crown): ?>
                    <option value="<?php echo htmlspecialchars($crown); ?>"><?php echo htmlspecialchars($crown); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="nama_pelanggan" required>
                <option value="">Pilih Pelanggan</option>
                <?php foreach ($pelanggan_options as $pelanggan): ?>
                    <option value="<?php echo htmlspecialchars($pelanggan); ?>"><?php echo htmlspecialchars($pelanggan); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="tanggal_pinjam" placeholder="Tanggal Pinjam" required />
            <input type="date" name="tanggal_kembali" placeholder="Tanggal Kembali" required />
            <button type="submit" name="submit">Tambah Transaksi</button>
        </form>

        <a href="daftar_transaksi.php" class="link">Daftar Transaksi</a>
        <a href="dashboard.php" class="link">Kembali</a>
    </div>
</body>
</html>

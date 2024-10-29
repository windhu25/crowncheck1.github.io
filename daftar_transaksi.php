<?php
include "service/database.php";

// 1. Update stok dan status jika tanggal kembali telah lewat
$current_date = date('Y-m-d');

$update_stok_query = "
    UPDATE crown
    JOIN transaksi ON crown.crown_id = transaksi.crown_id
    SET crown.stok = crown.stok + 1,
        transaksi.status = 1
    WHERE transaksi.tanggal_kembali < ? AND transaksi.status = 0
";
$stmt_update_stok = $db->prepare($update_stok_query);
$stmt_update_stok->bind_param("s", $current_date);
$stmt_update_stok->execute();
$stmt_update_stok->close();

// 2. Update status pada crown berdasarkan stok
$update_status_query = "
    UPDATE crown
    SET status = CASE
        WHEN stok > 0 THEN 1
        ELSE 0
    END
";
$stmt_update_status = $db->prepare($update_status_query);
$stmt_update_status->execute();
$stmt_update_status->close();

// Fetch data with joins
$data_transaksi = [];
$query_transaksi = "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan, crown.nama_crown, crown.stok, crown.status AS crown_status 
    FROM transaksi 
    JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.pelanggan_id 
    JOIN crown ON transaksi.crown_id = crown.crown_id
";
$result_transaksi = $db->query($query_transaksi);

if ($result_transaksi) {
    while ($row = $result_transaksi->fetch_assoc()) {
        // Determine status transaksi
        if ($row['tanggal_kembali'] < $current_date) {
            $status_transaksi = 'Selesai';
        } elseif ($row['tanggal_pinjam'] > $current_date) {
            $status_transaksi = 'Akan Datang';
        } else {
            $status_transaksi = 'Berlangsung';
        }
        $row['status_transaksi'] = $status_transaksi;
        $data_transaksi[] = $row;
    }
} else {
    $message = "<div class='message error'>Error: " . $db->error . "</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
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
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h3 {
            color: #0277bd; /* Biru tua */
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #0277bd; /* Biru tua */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .link {
            display: inline-block;
            padding: 0.5rem 1rem;
            color: #0277bd; /* Biru tua */
            text-decoration: none;
            border: 1px solid #0277bd;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 1rem;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .link:hover {
            background-color: #0277bd;
            color: white;
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
    </style>
</head>
<body>
    <header>Daftar Transaksi</header>

    <div class="container">
        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <h3>Daftar Transaksi</h3>
        <?php if (!empty($data_transaksi)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Crown</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Stok Crown</th>
                        <th>Status Crown</th>
                        <th>Status Transaksi</th> <!-- Kolom baru -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_transaksi as $transaksi): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaksi['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaksi['nama_crown']); ?></td>
                            <td><?php echo htmlspecialchars($transaksi['nama_pelanggan']); ?></td>
                            <td><?php echo htmlspecialchars($transaksi['tanggal_pinjam']); ?></td>
                            <td><?php echo htmlspecialchars($transaksi['tanggal_kembali']); ?></td>
                            <td><?php echo htmlspecialchars($transaksi['stok']); ?></td>
                            <td><?php echo $transaksi['crown_status'] ? 'Tersedia' : 'Tidak Tersedia'; ?></td>
                            <td><?php echo htmlspecialchars($transaksi['status_transaksi']); ?></td> <!-- Status transaksi -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi untuk ditampilkan.</p>
        <?php endif; ?>

        <a href="transaksi.php" class="link">Kembali</a>
    </div>
</body>
</html>

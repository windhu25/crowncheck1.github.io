<?php
include "service/database.php"; 

if (isset($_POST["update"])) {
    $pelanggan_id = (int)$_POST["pelanggan_id"]; 
    $nama = $db->real_escape_string($_POST["nama"]); 

    $sql = "UPDATE pelanggan SET nama='$nama' WHERE pelanggan_id='$pelanggan_id'";

    if ($db->query($sql)) {
        $message = "<div class='message success'>Berhasil Mengupdate Nama Pelanggan</div>";
    } else {
        $message = "<div class='message error'>Error: " . $db->error . "</div>"; 
    }
}

if (isset($_GET['delete'])) {
    $pelanggan_id = (int)$_GET['delete'];

    $sql = "DELETE FROM pelanggan WHERE pelanggan_id='$pelanggan_id'";

    if ($db->query($sql)) {
        $message = "<div class='message success'>Berhasil Menghapus Nama Pelanggan</div>";
    } else {
        $message = "<div class='message error'>Error: " . $db->error . "</div>"; 
    }
}

$data = [];
$query = "SELECT * FROM pelanggan";
$result = $db->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; 
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
    <title>Daftar Pelanggan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: #fff;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .link {
            display: inline-block;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
        }
        .link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>Daftar Pelanggan</header>

    <div class="container">
        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <h3>Daftar Pelanggan</h3>
        <?php if (!empty($data)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($data as $pelanggan): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pelanggan['pelanggan_id']); ?></td>
                        <td><?php echo htmlspecialchars($pelanggan['nama']); ?></td>
                        <td>
                            <form action="lihat_pelanggan.php" method="POST" style="display:inline;">
                                <input type="hidden" name="pelanggan_id" value="<?php echo htmlspecialchars($pelanggan['pelanggan_id']); ?>">
                                <input type="text" name="nama" placeholder="Nama Baru" required />
                                <button type="submit" name="update">Update</button>
                            </form>
                            <a href="?delete=<?php echo htmlspecialchars($pelanggan['pelanggan_id']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');" class="link">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Tidak ada data untuk ditampilkan.</p>
        <?php endif; ?>

        <a href="pelanggan.php" class="link">Kembali</a>
    </div>
</body>
</html>

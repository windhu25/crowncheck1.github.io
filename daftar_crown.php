<?php
include "service/database.php"; // Pastikan file ini ada dan berfungsi dengan baik

// Fetch data
$data = [];
$query = "SELECT * FROM crown";
$result = $db->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Determine status based on stock value
        $row['status'] = $row['stok'] == 0 ? 'Sedang Dipinjam' : 'Tersedia';
        $data[] = $row; 
    }
} else {
    $message = "<div class='message error'>Terjadi kesalahan: " . $db->error . "</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Crown</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #343a40;
        }
        header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        .container:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 22px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .item-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            gap: 8px;
        }
        .item-container img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .item-info {
            flex: 1;
        }
        .item-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>Manajemen Crown</header>

    <div class="container">
        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <h3>Daftar Crown</h3>
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $item): ?>
                <div class="item-container">
                    <img src="<?php echo htmlspecialchars($item['foto']); ?>" alt="Foto Crown">
                    <div class="item-info">
                        <p><strong>Crown ID:</strong> <?php echo htmlspecialchars($item['crown_id']); ?></p>
                        <p><strong>Nama Crown:</strong> <?php echo htmlspecialchars($item['nama_crown']); ?></p>
                        <p><strong>Stok:</strong> <?php echo htmlspecialchars($item['stok']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($item['status']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada data untuk ditampilkan.</p>
        <?php endif; ?>

        <a href="crown.php">Kembali</a>
    </div>
</body>
</html>

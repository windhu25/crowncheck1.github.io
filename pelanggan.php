<?php
include "service/database.php"; 

if (isset($_POST["input"])) {
    $nama = $db->real_escape_string($_POST["nama"]); 

    $sql = "INSERT INTO pelanggan (nama) VALUES ('$nama')";

    if ($db->query($sql)) {
        $message = "<div class='message success'>Berhasil Menambahkan Nama Pelanggan</div>";
    } else {
        $message = "<div class='message error'>Error: " . $db->error . "</div>"; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
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
        }
        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            flex: 1;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
    <header>Tambah Pelanggan</header>

    <div class="container">
        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <h3>Masukkan Nama Pelanggan</h3>
        <form action="pelanggan.php" method="POST">
            <input type="text" placeholder="Nama" name="nama" required />
            <button type="submit" name="input">Tambahkan</button>
        </form> 

        <a href="daftar_pelanggan.php" class="link">Daftar Pelanggan</a>
        <a href="dashboard.php" class="link">Kembali</a>
    </div>
</body>
</html>

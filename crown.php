<?php
include "service/database.php"; // Pastikan file ini ada dan berfungsi dengan baik

// Handle form submissions for adding
if (isset($_POST["input"])) {
    $crown_id = $_POST["crown_id"];
    $nama_crown = $db->real_escape_string($_POST["nama_crown"]);
    $stok = (int) $_POST["stok"];
    $foto = $_FILES['foto']['name'];
    $upload_file = '';

    if (!empty($foto)) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($foto);

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); 
        }

        if (!move_uploaded_file($foto_tmp, $upload_file)) {
            $message = "<div class='message error'>Gagal mengunggah file</div>";
        }
    }

    $sql = "INSERT INTO crown (crown_id, nama_crown, foto, stok) VALUES ('$crown_id', '$nama_crown', '$upload_file', '$stok')";
    if ($db->query($sql)) {
        $message = "<div class='message success'>Item berhasil ditambahkan</div>";
    } else {
        $message = "<div class='message error'>Terjadi kesalahan: " . $db->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Crown</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            color: #495057;
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
            max-width: 600px;
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
        form {
            margin-bottom: 20px;
            display: grid;
            gap: 15px;
        }
        input[type="number"],
        input[type="text"],
        input[type="file"],
        button {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus,
        button:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
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

        <h3>Tambah Crown</h3>
        <form action="crown.php" method="POST" enctype="multipart/form-data">
            <input type="number" placeholder="Crown ID" name="crown_id" required />
            <input type="text" placeholder="Nama Crown" name="nama_crown" required />
            <input type="file" name="foto" />
            <input type="number" placeholder="Stok" name="stok" required />
            <button type="submit" name="input">Tambahkan</button>
        </form>

        <a href="daftar_crown.php">Daftar Crown</a>
        <a href="dashboard.php">Kembali</a>
    </div>
</body>
</html>

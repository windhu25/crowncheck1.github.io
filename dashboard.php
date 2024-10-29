<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Le Ciel Design</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* Link Buttons */
        .link {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 1.2em;
            text-align: center;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .link:hover {
            background-color: #0056b3;
        }
        /* Logout Button */
        .logout {
            background-color: #dc3545; /* Red for logout button */
        }
        .logout:hover {
            background-color: #c82333;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
            h1 {
                font-size: 2em;
            }
            .link {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SELAMAT DATANG DI DASHBOARD LE CIEL DESIGN</h1>
        <a href="crown.php" class="link">Crown</a>
        <a href="pelanggan.php" class="link">Pelanggan</a>
        <a href="transaksi.php" class="link">Transaksi</a>
        <a href="cari.php" class="link">Cari</a>
        <a href="index.php" class="link logout">Keluar</a> <!-- Tombol keluar -->
    </div>
</body>
</html>

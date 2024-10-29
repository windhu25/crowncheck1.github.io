<?php
include "service/database.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mengambil data pengguna jika login berhasil
        $data = $result->fetch_assoc();
        header("location: dashboard.php");
        exit();
    } else {
        $error_message = "Akun tidak terdaftar";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Le Ciel Design</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center; /* Menyusun isi container ke tengah */
        }
        h3 {
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
        header {
            background-color: #007BFF;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: absolute;
            top: 0; /* Letakkan header di atas */
        }
        h1 {
            color: white;
            margin: 0;
        }
        nav {
            margin-top: 10px;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }
    </style>
</head>
<body>
<header>
    <h1>Le Ciel Design</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

    <div class="container">
        <h3>SELAMAT DATANG</h3>
        <form action="login.php" method="POST">
            <input type="text" placeholder="Username" name="username" required />
            <input type="password" placeholder="Password" name="password" required />
            <button type="submit" name="login">Masuk</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>

<footer>
    <hr style="border: 1px solid #007BFF; width: 80%; margin: 5px auto;">
    <p style="margin: 0; color: #333;">by <strong>Windhu Tirta Handika</strong></p>
</footer>
</body>
</html>
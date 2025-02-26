<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_tiket_shella");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($email) || empty($password)) {
        $error_message = "Email dan password harus diisi.";
    } else {
        // Query untuk mendapatkan data pengguna berdasarkan email
        $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $hashed_password);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $hashed_password)) {
                // Login berhasil, simpan informasi pengguna di session
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name; // Simpan nama pengguna di session
                header("Location: index.php"); // Ganti dengan halaman yang sesuai setelah login
                exit();
            } else {
                $error_message = "Password salah.";
            }
        } else {
            $error_message = "Email tidak terdaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Cineplex</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *, *:before, *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
    background: url('images/bio.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: #ffffff;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 40px rgba(3, 35, 95, 0.2);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #333;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo-container img {
            width: 40px;
            height: 40px;
        }

        .logo-container h3 {
            font-size: 36px;
            font-weight: 700;
            line-height: 42px;
            text-align: center;
            color: red;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
            color: #333;
        }

        ::placeholder {
            color: #666;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #333;
            color: #ffffff;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<form method="POST" action="login.php">
        <div class="logo-container">
            <img src="assets/img/cinema2.png" alt="Cineplex Logo" style="width: 65px; height:65px;"> <!-- Ganti logo.png dengan path gambar logo -->
            <h3>Cineplex</h3>
        </div>
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email Anda">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password Anda">

        <a href="register.php">Lupa password?</a>


        <button type="submit" name="login" style="background-color: #5C2FC2;">Login</button>
    </form>
</body>
</html>

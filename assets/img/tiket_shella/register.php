<?php
// Menyertakan autoloader Composer
require 'vendor/autoload.php';  // Pastikan pathnya sesuai dengan struktur project Anda

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Inisialisasi variabel untuk menyimpan input
$name = '';
$email = '';
$password = '';

if (isset($_POST['send_otp'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Simpan password di session
    $_SESSION['password'] = $password;

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['otp_sent_time'] = time(); // Store the time OTP was sent

    // Kirim email OTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rahmawatisuci433@gmail.com';
        $mail->Password = 'jxdi gwgu bago qsuh';  // Gunakan App Password jika 2FA aktif
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Untuk port 465
        $mail->Port = 465;  // Port untuk SSL

        $mail->setFrom('rahmawatisuci433@gmail.com', 'Tiket sela');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verifikasi Akun';
        $mail->Body = "Hai $name, <br> Berikut adalah kode OTP Anda: <b>$otp</b>.<br>Kode ini berlaku selama 15 menit.";

        $mail->send();
        $otp_sent = true; // Set flag untuk menampilkan SweetAlert
    } catch (Exception $e) {
        echo "Gagal mengirim email: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['verify_otp'])) {
    $otp_input = $_POST['otp'];

    // Check if OTP is valid and not expired (15 minutes)
    if ($otp_input == $_SESSION['otp'] && (time() - $_SESSION['otp_sent_time'] < 900)) {
        // OTP valid, simpan data pengguna ke database
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);  // Hash password

        // Koneksi ke database dan insert data pengguna
        $conn = new mysqli("localhost", "root", "", "db_tiket_shella");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Use prepared statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $registration_success = true; // Set flag untuk menampilkan SweetAlert
            // Hapus session setelah verifikasi
            unset($_SESSION['otp']);
            unset($_SESSION['otp_sent_time']);
            unset($_SESSION['password']); // Hapus password dari session
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "OTP salah atau kadaluarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Design by foolishdeveloper.com -->
    <title>Glassmorphism login Form Tutorial in html css</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style>
        *, *:before, *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
            padding: 50px 35px;
            text-align: center;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #333;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
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
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
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

        .social {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .social div {
            width: 150px;
            border-radius: 3px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: center;
            cursor: pointer;
        }

        .social div:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .social .fb {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form action="register.php" method="POST">
    <div class="logo-container">
            <img src="assets/img/cinema2.png" alt="Cineplex Logo" > <!-- Ganti logo.png dengan path gambar logo -->
            <h3 style="color: red">Login Here</h3>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label> 
            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama Anda"
                value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email
                Address</label>
            <input type="email" class="form-control" name="email" placeholder="Masukkan Email Anda" value="<?php echo htmlspecialchars($email); ?>"
                required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required placeholder="Masukkan Password Anda">
        </div>
        <button type="submit" name="send_otp" class="btn btnprimary" style="background-color: #5C2FC2;">Kirim OTP</button>
    </form>

    <?php if (isset($_SESSION['otp'])): ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="otp" class="form-label">Masukan OTP</label> <input type="text" class="form-control" name="otp"
                    required>
            </div>
            <button type="submit" name="verify_otp" class="btn btnsuccess">Verifikasi OTP</button>
        </form>
    <?php endif; ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script> 
    // Menampilkan SweetAlert setelah mengirim OTP 
    <?php if (isset($otp_sent) && $otp_sent): ?>       Swal.fire({         title: 'OTP Terkirim!',         text: 'Kode OTP telah dikirim ke email Anda.',         icon: 'success',         confirmButtonText: 'OK' 
      }); 
    <?php endif; ?> 
 
    // // Menampilkan SweetAlert setelah pendaftaran berhasil 
    <?php if (isset($registration_success) && $registration_success): ?>       Swal.fire({         title: 'Pendaftaran Berhasil!',         text: 'Anda telah berhasil mendaftar. Silakan masuk.',         icon: 'success',         confirmButtonText: 'OK' 
      }).then(() => { 
        window.location.href='login.php';
        // // Mengarahkan pengguna ke register.php setelah menekan OK         window.location.href = 'authentication-register.php'; // Ganti dengan path yang sesuai 
      }); 
    <?php endif; ?> 
  </script> 

</html>
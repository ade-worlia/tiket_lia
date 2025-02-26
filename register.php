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

        $mail->setFrom('rahmawatisuci433@gmail.com', 'Tiket Lia');
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
        $conn = new mysqli("localhost", "root", "", "tiket_lia");
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
    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
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
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .social {
            margin-top: 30px;
            display: flex;
        }

        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }

        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }

        .social .fb {
            margin-left: 25px;
        }

        .social i {
            margin-right: 4px;
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form action="register.php" method="POST">
        <h3>Login Here</h3>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label> <input type="text" class="form-control" name="name"
                value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email
                Address</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>"
                required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" name="send_otp" class="btn btnprimary">Kirim OTP</button>
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
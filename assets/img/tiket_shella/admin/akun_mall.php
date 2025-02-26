<?php
// Menyertakan autoloader Composer
require '../vendor/autoload.php';  // Pastikan pathnya sesuai dengan struktur project Anda

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Inisialisasi variabel untuk menyimpan input
$name = '';
$email = '';
$password = '';

if (isset($_POST['send_otp'])) {
  $name = $_POST['nik'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $id = $_POST['id'];

  // Simpan password di session
  $_SESSION['password'] = $password;

  // Generate OTP
  $otp = rand(100000, 999999);
  $_SESSION['otp'] = $otp;
  $_SESSION['email'] = $email;
  $_SESSION['nik'] = $name;
  $_SESSION['id'] = $id;
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

    $mail->setFrom('rahmawatisuci433@gmail.com', 'tiket');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'OTP Verifikasi Akun';
    $mail->Body = "<br> Berikut adalah kode OTP Anda: <b>$otp</b>.<br>Kode ini berlaku selama 15 menit.";

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
    $name = $_SESSION['nik'];
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);  // Hash password

    // Koneksi ke database dan insert data pengguna
    $conn = new mysqli("localhost", "root", "", "db_tiket_shella");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement
    $stmt = $conn->prepare("UPDATE akun_mall SET nik = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $password, $id);


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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Tambahkan ini sebelum script jQuery digunakan -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container-scroller"> 
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row ">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5">
          <img src="images/logoooo.webp" class="mr-2" alt="logo" style="height: 60px; width: auto;" />CINEMA</a>


      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
              data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
              aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-settings mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Settings</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Private message
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">New user registration</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    2 days ago
                  </p>
                </div>
              </a>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="images/faces/face28.jpg" alt="profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme">
            <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
          </div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme">
            <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
          </div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>

      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <?php
      include "componen/sidebar.php"
        ?>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Akun mall</h4>
              <div class="table-responsive">
                <?php
                // Pastikan file koneksi sudah di-include
                include '../koneksi.php';

                // Query untuk mengambil data dari tabel admin
                $query = "SELECT * FROM akun_mall";
                $result = mysqli_query($conn, $query);

                // Periksa apakah ada data dalam tabel
                if (mysqli_num_rows($result) > 0) {
                  $no = 1; // Nomor urut
                  echo '<table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama mall</th>
                    <th>Nik</th>
                    <th>Email</th>
                    <th>password</th>
                     <th>Aksi</th>
                </tr>
            </thead>
            <tbody>';

                  // Gunakan while untuk menampilkan data
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . htmlspecialchars($row["nama_mall"]) . '</td>
                    <td>' . htmlspecialchars($row["nik"]) . '</td>
                    <td>' . htmlspecialchars($row["email"]) . '</td>
                    <td>*</td>
                    <td>  
                        <button class="btn btn-warning btn-edit"
                            data-id="' . $row['id'] . '"  
                            data-nama="' . htmlspecialchars($row['nama_mall']) . '"
                            data-nik="' . htmlspecialchars($row['nik']) . '"
                            data-email="' . htmlspecialchars($row['email']) . '"
                            data-toggle="modal"  
                            data-target="#modalTambahJadwal"> 
                            Edit 
                        </button> 
                    </td>
                </tr>';

                  }


                  echo '</tbody></table>';
                } else {
                  echo "Tidak ada data dalam tabel admin.";
                }

                // Tutup koneksi database (opsional)
                mysqli_close($conn);
                ?>
                <script>
                  $('.btn-edit').click(function () {
                    var id = $(this).data('id'); var nama = $(this).data('nama'); var nik = $(this).data('nik'); var email = $(this).data('email');

                    $('#edit-id').val(id);
                    $('#edit-nama').val(nama);
                    $('#edit-nik').val(nik);
                    $('#edit-email').val(email);
                  });

                </script>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Tambah Admin -->





        <script>
          // Menampilkan SweetAlert setelah mengirim OTP 
          <?php if (isset($otp_sent) && $otp_sent): ?>     Swal.fire({
              title: 'OTP Terkirim!', text: 'Kode OTP telah dikirim ke email Anda.', icon: 'success', confirmButtonText: 'OK'

            }).then((result) => {
              if (result.isConfirmed) {
                var myModal = new bootstrap.Modal(document.getElementById('modalTambahJadwal')); myModal.show();
              }
            });
                  <?php endif; ?>

          // // Menampilkan SweetAlert setelah pendaftaran berhasil 
          <?php if (isset($registration_success) && $registration_success): ?>     Swal.fire({
                      title: 'Pendaftaran Berhasil!', text: 'Anda telah berhasil Mengupdate.', icon: 'success', confirmButtonText: 'OK'
                    }).then(() => {
                      // Mengarahkan pengguna ke register.php setelah menekan OK       window.location.href = 'akun_mall.php'; // Ganti dengan path yang sesuai
                    });
                  <?php endif; ?> 
        </script>



        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2021.
              Premium
              <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
              BootstrapDash.
              All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i
                class="ti-heart text-danger ml-1"></i></span>
          </div>
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a
                href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>
          </div>
        </footer>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="js/dataTables.select.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
</body>
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" arialabelledby="modalTambahJadwalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahJadwalLabel">Edit Akun</h5> <button type="button" class="btn-close"
          data-bs-dismiss="modal" arialabel="Close"></button>
      </div>
      <div class="modal-body">
        <form action="akun_mall.php" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Nama Mall</label>
            <input type="text" class="form-control" name="name" id="edit-nama" value="<?php echo isset($_SESSION['nama_mall']) ?
              htmlspecialchars($_SESSION['nama_mall']) : ''; ?>" required>
          </div>
          <div class="mb-3">

            <input type="hidden" class="form-control" name="id" id="edit-id" value="<?php echo isset($_SESSION['id']) ? htmlspecialchars($_SESSION['id']) :
              ''; ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-nik" class="form-label">NIK</label>
            <input type="text" class="form-control" name="nik" id="edit-nik"
              value="<?php echo isset($_SESSION['nik']) ? htmlspecialchars($_SESSION['nik']) : ''; ?>" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label> <input type="email" class="form-control"
              name="email" id="editemail" value="<?php echo isset($_SESSION['email']) ?
                htmlspecialchars($_SESSION['email']) : ''; ?>" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Password</label> <input type="password" class="form-control"
              name="password" required>
          </div>
          <button type="submit" name="send_otp" class="btn btn-primary">Kirim
            OTP</button>
        </form>
        <?php if (isset($_SESSION['otp'])): ?>
          <form action="akun_mall.php" method="POST">
            <div class="mb-3">
              <label for="otp" class="form-label">Masukan OTP</label>
              <input type="text" class="form-control" name="otp" required>
            </div>
            <button type="submit" name="verify_otp" class="btn btn-success">Verifikasi OTP</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

</html>
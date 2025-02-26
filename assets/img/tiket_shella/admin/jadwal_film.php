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

    $mail->setFrom('shellasantika33@gmail.com', 'Tiket Shella');
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
    $stmt = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cineplex</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/cinema2.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <?php  ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-black" href="#" id="userDropdown" role="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-user"></i>
              <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "Guest"; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="#" onclick="confirmLogout(event)" data-bs-toggle="dropdown">
                <i class="fa fa-sign-out-alt"></i> Logout
              </a>

            </div>
          </li>
        </ul>

        <script>
          function confirmLogout(event) {
            event.preventDefault(); // Mencegah aksi default link
            event.stopPropagation(); // Mencegah dropdown tertutup
            if (confirm("Apakah Anda yakin ingin keluar?")) {
              window.location.href = "logout.php";
            }
          }
        </script>


        <style>
          .navbar-nav .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: auto;
            right: 0;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
          }

          .navbar-nav .nav-item.dropdown:hover .dropdown-menu {
            display: block;
          }
        </style>
       
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
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
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
      include 'componen/sidebar.php'
      ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
         <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Jadwal film</h4>
                            <button class="btn btn-primary mb-3" data-toggle="modal"
                                data-target="#modalTambahJadwal">Tambah Jadwal</button>
                            <div class="table-responsive">
                                <?php
                                // Pastikan file koneksi sudah di-include
                                include '../koneksi.php';


                                // Query untuk mengambil data dari tabel jadwal_film dan tabel terkait
                                $query = "SELECT jadwal_film.id, akun_mall.nama_mall, film.nama_film, film.poster,
                                    jadwal_film.total_menit, jadwal_film.tanggal_tayang, jadwal_film.tanggal_akhir_tayang,
                                    jadwal_film.jam_tayang_1, jadwal_film.jam_tayang_2, jadwal_film.jam_tayang_3,
                                    jadwal_film.studio
                            FROM jadwal_film
                            JOIN akun_mall ON jadwal_film.mall_id = akun_mall.id
                            JOIN film ON jadwal_film.film_id = film.id";
                                $result = mysqli_query($conn, $query);

                                // Periksa apakah ada data dalam tabel
                                if (mysqli_num_rows($result) > 0) {
                                    $no = 1; // Nomor urut
                                    echo '<table class="table">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama Mall</th>
                                            <th>Poster</th>
                                            <th>Nama Film</th>
                                            <th>Total Menit</th>
                                            <th>Tanggal Tayang</th>
                                            <th>Tanggal Akhir Tayang</th>
                                            <th>Jam Tayang 1</th>
                                            <th>Jam Tayang 2</th>
                                            <th>Jam Tayang 3</th>
                                            <th>Studio</th>
                                        </tr>
                                    </thead>
                   <tbody>';

                                    // Menampilkan data film
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Konversi tanggal ke format DateTime
                                        $expired_date = new DateTime($row['tanggal_akhir_tayang']);
                                        $current_date = new DateTime();

                                        // Cek apakah film sudah kadaluarsa
                                        $is_expired = $expired_date < $current_date;

                                        echo "<tr " . ($is_expired ? "style='background-color: red !important;'" : "") . "> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">{$no}</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['nama_mall']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . "><img src='../{$row['poster']}' alt='Poster Film' width='50'></td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['nama_film']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['total_menit']) . "</td>
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['tanggal_tayang']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['tanggal_akhir_tayang']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['jam_tayang_1']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['jam_tayang_2']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['jam_tayang_3']) . "</td> 
                <td " . ($is_expired ? "style='background-color: red !important;'" : "") . ">" . htmlspecialchars($row['studio']) . "</td> 
              </tr>";
                                        $no++;
                                    }

                                    echo '</tbody></table>';

                                } else {
                                    echo "Tidak ada data dalam tabel jadwal_film.";
                                }

                                // Tutup koneksi database (opsional)
                                mysqli_close($conn);
                                ?>


                            </div>
                        </div>
                    </div>
        </div>
        <div class="modal fade" id="modalTambahJadwal" tabindex="-1" arialabelledby="modalTambahJadwalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal Film</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    arialabel="Close">X</button>
                            </div>
                            <div class="modal-body">
                                <form id="formTambahJadwal">
                                    <!-- Nama Mall -->
                                    <div class="mb-3">
                                        <label for="namaMall" class="form-label">Nama Mall</label> <select
                                            class="form-select" id="namaMall" name="namaMall" required>
                                            <option value="">Pilih Mall</option>
                                        </select>
                                    </div>

                                    <!-- Nama Film -->
                                    <div class="mb-3">
                                        <label for="namaFilm" class="form-label">Nama Film</label> <select
                                            class="form-select" id="namaFilm" name="namaFilm" required>
                                            <option value="">Pilih Film</option>
                                        </select>
                                    </div>

                                    <!-- Poster -->
                                    <div class="mb-3">
                                        <label for="posterFilm" class="form-label">Poster</label> <img id="posterFilm"
                                            src="" alt="Poster Film" class="imgthumbnail"
                                            style="display: none; max-height: 200px;">
                                    </div>

                                    <!-- Total Menit -->
                                    <div class="mb-3">
                                        <label for="totalMenit" class="form-label">Total Menit</label> <input
                                            type="text" class="form-control" id="totalMenit" name="totalMenit" readonly>
                                    </div>

                                    <!-- Tanggal Tayang -->
                                    <div class="mb-3">
                                        <label for="tanggalTayang" class="form-label">Tanggal
                                            Tayang</label>
                                        <input type="date" class="form-control" id="tanggalTayang" name="tanggalTayang"
                                            required>
                                    </div>

                                    <!-- Tanggal Akhir Tayang -->
                                    <div class="mb-3">
                                        <label for="tanggalAkhirTayang" class="form-label">Tanggal Akhir Tayang</label>
                                        <input type="date" class="form-control" id="tanggalAkhirTayang"
                                            name="tanggalAkhirTayang" required>
                                    </div>

                                    <!-- Jam Tayang 1 -->
                                    <div class="mb-3">
                                        <label for="jamTayang1" class="form-label">Jam Tayang 1</label>
                                        <input type="time" class="form-control" id="jamTayang1" name="jamTayang1"
                                            required>
                                    </div>

                                    <!-- Jam Tayang 2 -->
                                    <div class="mb-3">
                                        <label for="jamTayang2" class="form-label">Jam Tayang 2</label>
                                        <input type="time" class="form-control" id="jamTayang2" name="jamTayang2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="jamTayang3" class="form-label">Jam Tayang 3</label> <input
                                            type="time" class="form-control" id="jamTayang3" name="jamTayang3">
                                    </div>

                                    <!-- Pilih Studio -->
                                    <div class="mb-3">
                                        <label for="studio" class="form-label">Pilih Studio</label>
                                        <select class="form-select" id="studio" name="studio" required>
                                            <option value="">Pilih Studio</option>
                                            <option value="Studio 1">Studio 1</option>
                                            <option value="Studio 2">Studio 2</option>
                                            <option value="Studio 3">Studio 3</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2021. Premium
              <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.
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
    <!-- page-body-wrapper ends -->
  </div>
  </div>

  <script>
    $(document).ready(function () {
        // Fetch mall data
        $.ajax({
            url: 'api.php?endpoint=mall',
            method: 'GET',
            success: function (data) {
                data.forEach(function (mall) {
                    $('#namaMall').append(`<option value="${mall.id}">${mall.nama_mall}</option>`);  // Perbaikan dengan backticks
                });
            }
        });

        // Fetch film data
        $.ajax({
            url: 'api.php?endpoint=film',
            method: 'GET',
            success: function (data) {
                data.forEach(function (film) {
                    $('#namaFilm').append(`<option value="${film.id}">${film.nama_film}</option>`);  // Perbaikan dengan backticks
                });
            }
        });

        // Handle film selection
        $('#namaFilm').change(function () {
            const filmId = $(this).val();
            if (filmId) {
                $.ajax({
                    url: `api.php?endpoint=film_detail&id=${filmId}`,  // Perbaikan dengan backticks
                    method: 'GET',
                    success: function (film) {
                        $('#posterFilm').attr('src', `../${film.poster}`).show();  // Perbaikan dengan backticks
                        $('#totalMenit').val(film.total_menit);
                    },
                    error: function () {
                        $('#posterFilm').hide().attr('src', '');
                        $('#totalMenit').val('');
                    }
                });
            } else {
                $('#posterFilm').hide().attr('src', '');
                $('#totalMenit').val('');
            }
        });

        // Handle form submission
        $('#formTambahJadwal').submit(function (e) {
            e.preventDefault();

            // Get form data
            const formData = $(this).serialize();

            // Send data to server
            $.ajax({
                url: 'api.php?endpoint=tambah_jadwal',
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Show SweetAlert2 on success
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Jadwal Film berhasil disimpan!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to jadwal.php
                                window.location.href = 'jadwal_film.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menyimpan jadwal film.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Terjadi kesalahan!',
                        text: 'Tidak dapat menyimpan jadwal film.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                }
            });
        });
    });
</script>

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

</html>


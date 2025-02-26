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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo"/></a>
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
          
          
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
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
      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
          </li>
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
              </ul>
            </div>
            <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary mr-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
              <p class="text-gray mb-0">The total number of sessions</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary mr-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div>
          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div>
          <!-- chat tab ends -->
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
              <h4 class="card-title">Daftar Admin</h4>
              <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahAdminModal">Tambah
                Admin</button>
              <div class="table-responsive">
                <?php
                // Pastikan file koneksi sudah di-include
                include '../koneksi.php';

                // Query untuk mengambil data dari tabel admin
                $query = "SELECT * FROM admin";
                $result = mysqli_query($conn, $query);

                // Periksa apakah ada data dalam tabel
                if (mysqli_num_rows($result) > 0) {
                  $no = 1; // Nomor urut
                  echo '<table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>';

                  // Gunakan while untuk menampilkan data
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars($row["name"]) . '</td>
                <td>' . htmlspecialchars($row["email"]) . '</td>
                <td>*</td>
              </tr>';
                  }

                  echo '</tbody></table>';
                } else {
                  echo "Tidak ada data dalam tabel admin.";
                }

                // Tutup koneksi database (opsional)
                mysqli_close($conn);
                ?>

              </div>
            </div>
          </div>
        </div>

        <!-- Modal Tambah Admin -->
        <div class="modal fade" id="tambahAdminModal" tabindex="-1" role="dialog"
          aria-labelledby="tambahAdminModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="tambahAdminModalLabel">Tambah Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="admin.php">
                  <div class="form-group">
                    <label for="adminNama">Name</label>
                    <input type="text" class="form-control" name="name" id="adminNama" placeholder="Masukkan nama admin"
                      value="<?php echo htmlspecialchars($name); ?>">
                  </div>
                  <div class="form-group">
                    <label for="adminEmail">Email</label>
                    <input type="email" class="form-control" name="email" id="adminEmail"
                      placeholder="Masukkan email admin" value="<?php echo htmlspecialchars($email); ?>">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password"
                      placeholder="Masukkan Password admin">
                  </div>


              </div>
              <div class="modal-footer">
                <?php if (!isset($_SESSION['otp'])): ?>
                  <!-- Jika OTP belum dikirim -->
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="submit" name="send_otp" class="btn btn-primary">Kirim OTP</button>
                <?php else: ?>
                  <!-- Jika OTP sudah dikirim, tampilkan form verifikasi -->
                  <form action="admin.php" method="POST" class="w-100">
                    <div class="mb-3">
                      <label for="otp" class="form-label">Masukkan OTP</label>
                      <input type="text" class="form-control" name="otp" required>
                    </div>
                    <button type="submit" name="verify_otp" class="btn btn-success">Verifikasi OTP</button>
                  </form>
                <?php endif; ?>
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


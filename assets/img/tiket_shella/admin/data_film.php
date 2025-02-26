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
                            <h4 class="card-title">Data Film</h4>

                            <!-- Tombol Tambah Film -->
                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                                data-target="#modalTambahFilm">
                                Tambah Film
                            </button>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Poster</th>
                                            <th>Nama Film</th>
                                            <th>Deskripsi</th>
                                            <th>Genre</th>
                                            <th>Total Menit</th>
                                            <th>Usia</th>
                                            <th>Dimensi</th>
                                            <th>Producer</th>
                                            <th>Director</th>
                                            <th>Writter</th>
                                            <th>Cast</th>
                                            <th>Distributor</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Koneksi ke database
                                        include('../koneksi.php');

                                        // Query untuk mengambil data dari tabel film
                                        $query = "SELECT * FROM film_shella";  // Ganti 'film' dengan nama tabel yang sesuai
                                        $result = mysqli_query($conn, $query);

                                        if ($result) {
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td><img src='../" . $row['poster'] . "' alt='Poster' width='100'></td>";
                                                echo "<td>" . $row['nama_film'] . "</td>";
                                                echo "<td>" . $row['judul'] . "</td>";
                                                echo "<td>" . $row['genre'] . "</td>";
                                                echo "<td>" . $row['total_menit'] . "</td>";
                                                echo "<td>" . $row['usia'] . "</td>";
                                                echo "<td>" . $row['dimensi'] . "</td>";
                                                echo "<td>" . $row['producer'] . "</td>";
                                                echo "<td>" . $row['director'] . "</td>";
                                                echo "<td>" . $row['writer'] . "</td>";
                                                echo "<td>" . $row['cast'] . "</td>";
                                                echo "<td>" . $row['distributor'] . "</td>";
                                                echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete.php?id=" . $row['id'] . "'>Delete</a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal fade" id="modalTambahFilm" tabindex="-1" role="dialog" aria-labelledby="modalTambahFilmLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahFilmLabel">Tambah Film</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="../proses_input.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="poster">Upload Poster</label>
                                        <input type="file" class="form-control" id="poster" name="poster"
                                            accept="image/*" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_film">Nama Film</label>
                                        <input type="text" class="form-control" id="nama_film" name="nama_film"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_film">Deskripsi</label>
                                        <input type="text" class="form-control" id="judul" name="judul" required>
                                    </div>
                                    <script>
                                        const selectedGenres = new Set();

                                        function addGenre() {
                                            const genreSelect = document.getElementById('genreSelect');
                                            const selectedValue = genreSelect.value;

                                            if (selectedValue && !selectedGenres.has(selectedValue)) {
                                                selectedGenres.add(selectedValue);

                                                const listItem = document.createElement('li');
                                                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                                                listItem.textContent = selectedValue;

                                                const removeBtn = document.createElement('button');
                                                removeBtn.className = 'btn btn-sm btn-danger';
                                                removeBtn.textContent = 'hapus';
                                                removeBtn.onclick = () => {
                                                    selectedGenres.delete(selectedValue);
                                                    listItem.remove();
                                                    updateModalInput();
                                                };

                                                listItem.appendChild(removeBtn);
                                                document.getElementById('selectedGenres').appendChild(listItem);

                                                updateModalInput();
                                            }

                                            genreSelect.value = "";
                                        }

                                        function updateModalInput() {
                                            document.getElementById('genreInput').value = Array.from(selectedGenres).join(',');
                                        }
                                    </script>

                                    <div class="form-group">
                                        <label for="genre">Genre</label>
                                        <select class="form-control" id="genreSelect">
                                            <option value="" disabled selected>Pilih Genre</option>
                                            <option value="Action">Action</option>
                                            <option value="Adventure">Adventure</option>
                                            <option value="Animation">Animation</option>
                                            <option value="Biography">Biography</option>
                                            <option value="Comedy">Comedy</option>
                                            <option value="Crime">Crime</option>
                                            <option value="Disaster">Disaster</option>
                                            <option value="Documentary">Documentary</option>
                                            <option value="Drama">Drama</option>
                                            <option value="Epic">Epic</option>
                                            <option value="Erotic">Erotic</option>
                                            <option value="Experimental">Experimental</option>
                                            <option value="Family">Family</option>
                                            <option value="Fantasy">Fantasy</option>
                                            <option value="Film-Noir">Film-Noir</option>
                                            <option value="History">History</option>
                                            <option value="Horror">Horror</option>
                                            <option value="Martial Arts">Martial Arts</option>
                                            <option value="Music">Music</option>
                                            <option value="Musical">Musical</option>
                                            <option value="Mystery">Mystery</option>
                                            <option value="Political">Political</option>
                                            <option value="Psychological">Psychological</option>
                                            <option value="Romance">Romance</option>
                                            <option value="Sci-Fi">Sci-Fi</option>
                                            <option value="Sport">Sport</option>
                                            <option value="Superhero">Superhero</option>
                                            <option value="Survival">Survival</option>
                                            <option value="Thriller">Thriller</option>
                                            <option value="War">War</option>
                                            <option value="Western">Western</option>
                                        </select>

                                        <button type="button" class="btn btn-primary"
                                            onclick="addGenre()">Tambah</button>
                                    </div>
                                    <ul id="selectedGenres" class="mt-3 list-group d-flex flex-warp"
                                        style="max-height: 200px; overflow-y: auto;"></ul>
                                    <input type="hidden" id="genreInput" name="genre">

                                    <div class="form-group">
                                        <label for="cast">Cast</label>
                                        <input type="text" class="form-control" id="cast" name="cast" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="banner">Upload Banner</label>
                                        <input type="file" class="form-control" id="banner" name="banner"
                                            accept="image/*" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="menit">Total Menit</label>
                                        <input type="text" class="form-control" id="menit" name="menit" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="usia">Usia</label>
                                        <select class="form-control" id="usia" name="usia" required>
                                            <option value="" disabled selected>Pilih Usia</option>
                                            <option value="13">13</option>
                                            <option value="17">17</option>
                                            <option value="SU">SU</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="trailer">Upload Trailer</label>
                                        <input type="file" class="form-control" id="trailer" name="trailer"
                                            accept="video/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="distributor">Distributor</label>
                                        <input type="text" class="form-control" id="distributor" name="distributor"
                                            required>
                                    </div>

                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="dimensi">Berapa Dimensi</label>
                                        <select class="form-control" id="dimensi" name="dimensi" required>
                                            <option value="" disabled selected>Pilih Dimensi</option>
                                            <option value="2D">2D</option>
                                            <option value="3D">3D</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="producer">Producer</label>
                                        <input type="text" class="form-control" id="producer" name="producer" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="director">Director</label>
                                        <input type="text" class="form-control" id="director" name="director" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="writer">Writer</label>
                                        <input type="text" class="form-control" id="writer" name="writer" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="harga">Harga Per Tiket</label>
                                        <input type="number" class="form-control" id="harga" name="harga" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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


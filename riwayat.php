<?php
session_start();
include 'koneksi.php'; 
$sql = "SELECT * FROM transactions ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Dewi Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
  <link href="https://cdn.datables.net/buttons/2.3.3/css/buttons.dataTables.min.css" rel="stylesheet"> 

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

<?php include "componen/navbar.php"; ?> 

<main class="main">
  <!-- Hero Section -->
  <!-- /Hero Section -->
  <!-- About Section -->
  <!-- /About Section -->
</main>

<!-- Table Riwayat Transaksi -->
<div class="container mt-4">
  <h3>Riwayat Transaksi</h3>
  
  <?php
  $username = isset($_GET['username']) ? $_GET['username'] : '';

  $sql = "SELECT * FROM transactions WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  ?>

  <div class="table-responsive">
    <table id="transactionTable" class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>ID Transaksi</th>
          <th>Email</th>
          <th>Nama Film</th>
          <th>Nomor Kursi</th>
          <th>Tanggal Pembayaran</th>
          <th>Jenis Pembayaran</th>
          <th>Harga</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
          <td>{$no}</td>
          <td>{$row['order_id']}</td>
          <td>{$row['username']}</td>
          <td>{$row['nama_film']}</td>
          <td>{$row['seat_number']}</td>
          <td>{$row['transaction_time']}</td>
          <td>{$row['payment_type']}</td>
          <td>Rp. {$row['amount']}</td>
          <td>";

          if ($row['status'] == 'settlement') {
            echo 'Selesai';
          } elseif ($row['status'] == 'pending') {
            echo 'Menunggu Pembayaran';
          } else {
            echo $row['status'];
          }

          echo "</td></tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>

</body>
</html>

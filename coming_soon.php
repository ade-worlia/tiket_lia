<?php
include 'koneksi.php';

$tanggal_hari_ini = date('Y-m-d');


$sql = "SELECT f.id,f.genre,f.total_menit, f.nama_film, f.poster, f.usia, MIN(j.tanggal_tayang) AS tanggal_tayang
 FROM film f
 INNER JOIN jadwal_film j ON f.id = j.film_id
 WHERE j.tanggal_tayang >?
 GROUP BY f.id, f.nama_film, f.poster, f.usia
 ORDER BY tanggal_tayang ASC";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query error: " . $conn->error);
}
$stmt->bind_param("s", $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Halaman Utama</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Dewi
  * Template URL: https://bootstrapmade.com/dewi-free-multi-purpose-html-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <?php include "componen/navbar.php"; ?>
  <!-- Hero Section -->
  <main class="main">

    <!-- Hero Section -->
    <!-- /Hero Section -->
    <!-- About Section -->
    <!-- /About Section -->
    <!-- Stats Section -->
    <!-- /Stats Section -->
    <!-- Services Section -->
    <!-- /Services Section -->
    <!-- Clients Section -->
    <!-- /Clients Section -->
    <!-- Features Section -->
    <!-- /Features Section -->
    <!-- Services 2 Section -->
    <!-- /Services 2 Section -->
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section dark-background">
      <div class="container" data-aos="fade-up" dat
      
      a-aos-delay="100">
        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 1,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
        </div>
      </div>
    </section>

    <style>
      .full-slide {
        width: 100%;
        height: 100vh;
        object-fit: cover;
      }
    </style>
    <!-- /Testimonials Section -->

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">
    <div class="container section-title" data-aos="fade-up" style="text-align: center; display: flex; align-items: center; justify-content: center; gap: 20px;">
  <hr style="flex: 1; border: none; height: 2px; background-color: black;">
  <div>
    
    <p style="margin: 0;">COMINGSOON</p>
  </div>
  <hr style="flex: 1; border: none; height: 2px; background-color: black;">
</div>

<!-- End Section Title -->

    <div class="container">
      <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
        <style>
          .portfolio-content img {
            width:90%;
            /* Supaya gambar penuh dalam container */
            height: auto;
            /* Menjaga aspek rasio gambar */
            object-fit: cover;
            /* Memastikan gambar mengisi area tanpa distorsi */
          }
        </style>

        <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
          <?php while ($row = $result->fetch_assoc()): ?>

            <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
    <div class="card h-100 shadow-lg border-0 bg-dark text-white overflow-hidden position-relative rounded-3">
        <div class="position-relative">
            <img src="<?php echo $row['poster']; ?>" class="card-img-top poster-img" alt="<?php echo $row['nama_film']; ?>">
            <div class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 rounded-bottom shadow">
                <?php echo $row['usia']; ?>+
            </div>
            
        </div>
        <div class="card-body text-center">
            <h5 class="card-title fw-bold"><?php echo $row['nama_film']; ?></h5>
            <p class="text-white-50 small"><?php echo $row['genre']; ?> • <?php echo $row['total_menit']; ?> Menit</p>
            <a href="film.php?id=<?php echo $row['id'];?>" class="btn btn-outline-warning mt-2">Selengkapnya</a>
        </div>
    </div>
</div>

<style>
    /* Ukuran poster seragam */
    .poster-img {
        width: 100%;
        height: 400px; /* Sesuaikan ukuran */
        object-fit: cover; /* Biar gambar nggak gepeng */
    }

    /* Efek hover */
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .card:hover .overlay {
        opacity: 1;
    }
</style>



          <?php endwhile; ?>
          <!-- End Portfolio Item -->

        </div><!-- End Portfolio Container -->

      </div>

    </div>

  </section><!-- /Portfolio Section -->

    <!-- Team Section -->
    <!-- /Team Section -->

    <!-- Contact Section -->
    <!-- /Contact Section -->

  </main>

  <!-- /Hero Section -->

  <!-- About Section -->
  <!-- /About Section -->

  <!-- Stats Section -->
  <!-- /Stats Section -->

  <!-- Services Section -->
  <!-- /Services Section -->

  <!-- Clients Section -->


  <!-- /Clients Section -->

  <!-- Features Section -->
  <!-- /Features Section -->

  <!-- Services 2 Section -->
  <!-- /Services 2 Section -->

  <!-- Testimonials Section -->


 

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
<?php
session_start();
include 'koneksi.php';

$id_film = isset($_GET['id'])? intval($_GET['id']) : 0;

$sql = "SELECT * FROM film where id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_film);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $film = $result->fetch_assoc();
}else{
    echo "Film tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Halaman utama</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

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
<style>
        body{
            padding: 60px;
        }
        .movie-info{
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .movie-info img{
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
        .movie-details{
            flex: 1;
        }
        .btn-custom{
            background-color:maroon;
            color: white;
            margin-bottom: 10px;
            
        }
        .btn-custom:hover{

        background-color:grey;
    }
    @media (max-width: 768px){
        .movie-info{
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .movie-details{
            max-width: 100%;
        }
        
    }

    </style>
<body class="index-page">

  <?php include "componen/navbar.php"; ?>
<main>
    <section>
        <div class="container">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-btn-custom text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 40px ; height: 40px;">
                <span class="fw-bold" ><?php echo $film ['usia']; ?>+</span>
            </div>
            <div class="ms-3">
                <h5 class="mb-0"><?php echo $film['nama_film'];?></h5>
                <p class="text-muted mb-0"><?php echo $film['genre'];?></p>
            </div>
        </div>

        <div class="row movie-info">
            <div class="col-md-4 text-center">
                <img class="img-fluid rounded" alt="poster" src="<?php echo $film['poster'];?>">
            </div>
            <div class="col-md-8 movie-details">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-clock me-2"></i>
                    <span><?php echo $film['total_menit'];?></span>
                </div>
                <div class="mb-3">
                    <button class="btn btn-outline-secondary me-2"><?php echo $film['dimensi'];?></button>

                </div>
                <div class="mb-3">
                    <button class="btn btn-custom w-100" onclick="window.location.href='jadwal.php?id=<?php echo $film['id'];?>'">buy tiket</button>
                    <button class="btn btn-custom w-100" data-bs-toggle="modal" data-bs-target="#trailerModal">TRAILER</button>
                </div>
                <P><?php echo $film['judul']; ?></P>
                <p><strong>Producer:</strong><?php echo $film['Producer']?></p>
                <p><strong>Director:</strong><?php echo $film['Director']?></p>
                <p><strong>Writer:</strong><?php echo $film['Writer']?></p>
                <p><strong>Cast:</strong><?php echo $film['Cast']?></p>
                <p><strong>Distributor:</strong><?php echo $film['Distributor']?></p>

            </div>

        </div>

    </div></section>
    
    
    <div class="modal fade" id="trailerModal" tabindex="-1" aria-labelledby="trailerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trailerModalLabel">Trailer: <?php echo $film['nama_film'];?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <div class="modal-body">
                    <video width="100%" controls>
                        <source src="<?php echo $film['trailer'];?>" type="video/mp4">
                    </video>
                </div>

            </div>
        </div>

    </div>

</main>

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
  

  
  <!-- /Testimonials Section -->

  <!-- Portfolio Section -->
  <!-- /Portfolio Section -->

  <!-- Team Section -->
  <!-- /Team Section -->

  <!-- Contact Section -->
  <!-- /Contact Section -->

    

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->


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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah</title>
</head>

<body>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
<?php
include 'koneksi.php'; // Menghubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_film = $_POST['nama_film'];
    $judul = $_POST['judul'];
    $usia = $_POST['usia'];
    $genre = $_POST['genre']; // Genre yang dipilih
    $menit = $_POST['menit'];
    $dimensi = $_POST['dimensi'];
    $producer = $_POST['producer'];
    $director = $_POST['director'];
    $writer = $_POST['writer']; // Genre yang dipilih
    $cast = $_POST['cast'];
    $distributor = $_POST['distributor'];
    $harga = $_POST['harga'];

    // Mengupload file poster
    $target_dir_poster = "uploads/poster/"; // Folder untuk menyimpan file poster
    $target_file_poster = $target_dir_poster . basename($_FILES["poster"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file_poster, PATHINFO_EXTENSION));
    // Cek apakah file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["poster"]["tmp_name"]);
    if ($check === false) {
        echo "File yang diupload bukan gambar.";
        $uploadOk = 0;
    }
    // Cek ukuran file
    if ($_FILES["poster"]["size"] > 5000000) { // 500KB
        echo "Maaf, ukuran file poster terlalu besar.";
        $uploadOk = 0;
    }
    // Cek format file
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }
    // Cek apakah $uploadOk di-set ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file poster tidak dapat diupload.";
    } else {
        // Jika semua cek lulus, coba untuk mengupload file poster
        if (move_uploaded_file(
            $_FILES["poster"]["tmp_name"],
            $target_file_poster
        )) {
            // Mengupload file banner
            $target_dir_banner = "uploads/banner/"; // Folder untuk menyimpan file banner
            $target_file_banner = $target_dir_banner .
                basename($_FILES["banner"]["name"]);
            $uploadOkBanner = 1;
            $imageFileTypeBanner = strtolower(pathinfo(
                $target_file_banner,
                PATHINFO_EXTENSION
            ));
            // Cek apakah file gambar adalah gambar sebenarnya
            $checkBanner = getimagesize($_FILES["banner"]["tmp_name"]);
            if ($checkBanner === false) {
                echo "File yang diupload sebagai banner bukan gambar.";
                $uploadOkBanner = 0;
            }
            // Cek ukuran file banner
            if ($_FILES["banner"]["size"] > 500000) { // 500KB
                echo "Maaf, ukuran file banner terlalu besar.";
                $uploadOkBanner = 0;
            }
            // Cek format file banner
            if (!in_array($imageFileTypeBanner, [
                'jpg',
                'png',
                'jpeg',
                'gif'
            ])) {
                echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan 
    untuk banner.";
                $uploadOkBanner = 0;
            }
            // Cek apakah $uploadOkBanner di-set ke 0 oleh kesalahan
            if ($uploadOkBanner == 0) {
                echo "Maaf, file banner tidak dapat diupload.";
            } else {
                // Jika semua cek lulus, coba untuk mengupload file banner
                if (move_uploaded_file(
                    $_FILES["banner"]["tmp_name"],
                    $target_file_banner
                )) {
                    // Mengupload file trailer
                    $target_dir_trailer = "uploads/trailer/"; // Folder untuk menyimpan trailer
                    $target_file_trailer = $target_dir_trailer .
                        basename($_FILES["trailer"]["name"]);
                    $uploadOkTrailer = 1;
                    $videoFileType = strtolower(pathinfo($target_file_trailer, PATHINFO_EXTENSION));
                    // Cek format file trailer
                    if (!in_array($videoFileType, ['mp4', 'avi', 'mov', 'wmv'])) {
                        echo "Maaf, hanya file MP4, AVI, MOV & WMV yang 
    diperbolehkan.";
                        $uploadOkTrailer = 0;
                    }
                    // Cek ukuran file trailer
                    if ($_FILES["trailer"]["size"] > 50000000) { // 50MB
                        echo "Maaf, ukuran file trailer terlalu besar.";
                        $uploadOkTrailer = 0;
                    }
                    // Cek apakah $uploadOkTrailer di-set ke 0 oleh kesalahan
                    if ($uploadOkTrailer == 0) {
                        echo "Maaf, file trailer tidak dapat diupload.";
                    } else {
                        // Jika semua cek lulus, coba untuk mengupload file 

                        if (move_uploaded_file(
                            $_FILES["trailer"]["tmp_name"],
                            $target_file_trailer
                        )) {
                            // Menyiapkan dan mengeksekusi query
                            $stmt = $conn->prepare("INSERT INTO film (poster, 
    trailer, banner, nama_film, judul, total_menit, usia, genre, 
    dimensi,Producer,Director,Writer,Cast,Distributor,harga) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?)");
                            $stmt->bind_param(
                                "sssssssssssssss",
                                $target_file_poster,
                                $target_file_trailer,
                                $target_file_banner,
                                $nama_film,
                                $judul,
                                $menit,
                                $usia,
                                $genre,
                                $dimensi,
                                $producer,
                                $director,
                                $writer,
                                $cast,
                                $distributor,
                                $harga
                            );
                            if ($stmt->execute()) {
                                echo "<script> Swal.fire({
    title: 'Berhasil!',
    text: 'Data Film Berhasil Di Tambahkan !!',
    icon: 'success',
    timer: 3000, // 3 detik
    showConfirmButton: false
    }).then(() => {
    window.location.href = 'admin/data_film.php'; 
    });</script>";
                            } else {
                                echo "Error: " . $stmt->error;
                            }
                            $stmt->close();
                        } else {
                            echo "Maaf, terjadi kesalahan saat mengupload file 
    trailer.";
                        }
                    }
                } else {
                    echo "Maaf, terjadi kesalahan saat mengupload file 
    banner.";
                }
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file poster.";
        }
    }
}
$conn->close();
?>
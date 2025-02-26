<?php
session_start();
  $current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
  .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    /* Biar ketengah */
    gap: 10px;
    /* Jarak antara gambar dan teks */
    /* Biar bisa ngisi ruang tengah */
    text-align: center;
  }

  .logo img {
    height: 60px;
    /* Bisa sesuaikan ukurannya */
    width: auto;
  }

  .logo h3 {
    margin: 0;
    font-size: 24px;
  }

  .header .container-fluid {
    display: flex;
    justify-content: center;
    /* Tengahin semua isi header */
    background-color:black; 
    align-items: center;
    height: 80px;
  }

  .search-container {
    display: flex;
    align-items: center;
    gap: 10px;
    /* Jarak antara input dan tombol */
    margin-left: 20px;
    /* Geser dari menu */
  }

  .search-container input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 200px;
  }

  .search-container button {
    padding: 8px 12px;
    border: none;
    background-color: darkblue;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
  }

  .search-container button:hover {
    background-color: grey;
  }
</style>
<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">


    <a href="index.html" class="logo d-flex align-items-center me-auto">
    <img src="assets/img/lia.jpg" alt="Logo" class="logo-img me-2" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover;">

      <h1 class="sitename" style="color: white;" >Film's lia</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
      <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a></li>
    <li><a href="coming_soon.php" class="<?= $current_page == 'comingsoon.php' ? 'active' : '' ?>">Upcoming</a></li>
    <li><a href="teather.php" class="<?= $current_page == 'teather.php' ? 'active' : '' ?>">Theater</a></li>
        <li class="dropdown"><a>USIA<i class="bi bi-chevron-down toggle-dropdown"></i>
          <ul>
            <li><a href="usia.php?usia=SU">SU</a></li>
            <li><a href="usia.php?usia=13">13</a></li>
            <li><a href="usia.php?usia=17">17</a></li>
          </ul>
          </a>
        </li>
        <style>
          .dropdown ul {
            max-height: 400px;
            /* Sesuaikan tinggi maksimalnya */
            overflow-y: auto;
            background: white;
            /* Biar tetap kelihatan */
            border: 1px solid #ccc;
            /* Opsional, biar ada batasnya */
            padding: 5px;
          }
        </style>
        <li class="dropdown"><a href="#"><span>Genre</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li><a href="genre.php?genre=Action" class="<?= $current_page == 'genre.php' ? 'active' : '' ?>" >Action</a></li>
            <li><a href="genre.php?genre=Adventure">Adventure</a></li>
            <li><a href="genre.php?genre=Animation">Animation</a></li>
            <li><a href="genre.php?genre=Biography">Biography</a></li>
            <li><a href="genre.php?genre=Cartoon">Cartoon</a></li>
            <li><a href="genre.php?genre=Comedy">Comedy</a></li>
            <li><a href="genre.php?genre=Crime">Crime</a></li>
            <li><a href="genre.php?genre=Disaster">Disaster</a></li>
            <li><a href="genre.php?genre=Documentary">Documentary</a></li>
            <li><a href="genre.php?genre=Drama">Drama</a></li>
            <li><a href="genre.php?genre=Epic">Epic</a></li>
            <li><a href="genre.php?genre=Erotic">Erotic</a></li>
            <li><a href="genre.php?genre=Experimental">Experimental</a></li>
            <li><a href="genre.php?genre=Family">Family</a></li>
            <li><a href="genre.php?genre=Fantasy">Fantasy</a></li>
            <li><a href="Fim-Noir">Film-Noir</a></li>
            <li><a href="genre.php?genre=History">History</a></li>
            <li><a href="genre.php?genre=Horror">Horror</a></li>
            <li><a href="genre.php?genre=Martical_arts">Martial Arts</a></li>
            <li><a href="genre.php?genre=Music">Music</a></li>
            <li><a href="genre.php?genre=Musical">Musical</a></li>
            <li><a href="genre.php?genre=Mystery">Mystery</a></li>
            <li><a href="genre.php?genre=Political">Political</a></li>
            <li><a href="genre.php?genre=Pshychological">Psychological</a></li>
            <li><a href="genre.php?genre=Romance">Romance</a></li>
            <li><a href="genre.php?genre=Sci-Fi">Sci-Fi</a></li>
            <li><a href="genre.php?genre=Sport">Sport</a></li>
            <li><a href="genre.php?genre=Superhero">Superhero</a></li>
            <li><a href="genre.php?genre=Survival">Survival</a></li>
            <li><a href="genre.php?genre=Thiller">Thriller</a></li>
            <li><a href="genre.php?genre=War">War</a></li>
            <li><a href="genre.php?genre=Western">Western</a></li>

          </ul>
        </li>

      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
    <div class="search-container">
      <input type="text" id="searchMovie" placeholder="Cari Film.." onkeyup="searchGenre()">
      <button onclick="searchGenre()">Cari</button>
     <ul id="movieResults" class="absolute bg-white border border-gray-300 
      rounded-md w-full mt-1 shadow-md hidden overflow-y-auto max-h-52 z-50"></ul>
    </div>

<script>
      function searchGenre() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let genres = document.querySelectorAll('.dropdown ul li');

        genres.forEach(genre => {
          let text = genre.textContent.toLowerCase();
          if (text.includes(input)) {
            genre.style.display = "block";
          } else {
            genre.style.display = "none";
          }
        });
      }
    </script>
    <style>
      /* Styling dasar untuk dropdown */
      .nav-menu {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
      }

      .nav-menu .dropdown {
        position: relative;
      }

      .nav-menu .dropdown>a {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 10px 15px;
        color: white;
        font-weight: bold;
      }

      .nav-menu .dropdown ul {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border-radius: 5px;
        box-shadow: grey;
        list-style: none;
        padding: 5px 0;
        margin: 0;
        width: 150px;
        display: none;
        /* Supaya dropdown tersembunyi awalnya */
      }

      .nav-menu .dropdown ul li {
        padding: 10px;
      }

      .nav-menu .dropdown ul li a {
        text-decoration: none;
        color: black;
        display: block;
        padding: 8px 15px;
      }

      .nav-menu .dropdown:hover ul {
        display: block;
        /* Munculkan dropdown ketika di-hover */
      }

      /* Biar dropdown nggak tampil aneh */
      .user-dropdown {
        margin-left: 20px;
      }

      .nav-menu .dropdown ul li a:hover {
        background: #f1f1f1;
      }

      /* Warna teks login/logout */
      .nav-menu .dropdown a {
        color: white;
        /* Sesuaikan warna teks */
      }

      #header {
        background-color: white;
      }
      #movieResults {
    position: absolute;
    top: calc(100% + 5px); /* Muncul tepat di bawah input dengan sedikit jarak */
    left: 0;
    width: auto;
    min-width: 100%; /* Minimal selebar input */
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999;
    color: black;
    display: none;
    white-space: nowrap; /* Biar teks gak turun ke bawah */
}

/* Hover effect */
#movieResults li:hover {
    background: #f1f1f1;
}


/* Styling untuk setiap hasil pencarian */
#movieResults li {
    padding: 10px;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
}

/* Hover effect */
#movieResults li:hover {
    background: #f1f1f1;
}
    </style>


<script>
      document.addEventListener("DOMContentLoaded", function () {
        let dropdowns = document.querySelectorAll(".dropdown");

        dropdowns.forEach(function (dropdown) {
          let toggle = dropdown.querySelector(".toggle-dropdown");

          if (toggle) {
            toggle.addEventListener("click", function (e) {
              e.preventDefault();
              let menu = dropdown.querySelector("ul");
              if (menu.style.display === "block") {
                menu.style.display = "none";
              } else {
                menu.style.display = "block";
              }
            });
          }
        });
      });
      </script>

<ul class="nav-menu">
      <?php if (isset($_SESSION['name'])): ?>
        <li class="dropdown">
          <a href="#"><span><?= htmlspecialchars($_SESSION['name']) ?></span>
            <i class="bi bi-chevron-down toggle-dropdown"></i>
          </a>
          <ul>
            <li><a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="fa fa-sign-out"></i> Logout
              </a></li>
             <li><a class="dropdown-item" href="riwayat.php?username=<?php echo $_SESSION['email']; ?>">Riwayat Transaksi</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li><a class="btn btn-secondary" style="background-color:drakblue;" href="login.php"><i class="icon_profile"></i> Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>
<script>

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchMovie");
  const resultsList = document.getElementById("movieResults");

  searchInput.addEventListener("input", function () {
    const query = this.value.trim();
    resultsList.innerHTML = "";

    if (query.length > 0) {
      fetch(`get_movies.php?q=${query}`)
        .then(response => response.json())
        .then(data => {
          if (data.length === 0) {
            resultsList.style.display = "none";
            return;
          }

          resultsList.style.display = "block";
          resultsList.style.width = "auto";
          resultsList.style.minWidth = searchInput.offsetWidth + "px"; // Sesuaikan dengan input
          resultsList.style.left = searchInput.offsetLeft + "px"; // Supaya sejajar

          data.forEach(movie => {
            const li = document.createElement("li");
            li.textContent = movie.nama_film;
            li.className = "p-2 hover:bg-blue-100 cursor-pointer transition-all duration-200";

            li.addEventListener("click", () => {
              window.location.href = `film.php?id=${movie.id}`;
            });

            resultsList.appendChild(li);
          });
        })
        .catch(error => console.error("Error fetching data:", error));
    } else {
      resultsList.style.display = "none";
    }
  });

  document.addEventListener("click", function (e) {
    if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
      resultsList.style.display = "none";
    }
  });
});


</script>
<script>
      function searchGenre() {
          let input = document.getElementById('searchInput').value.toLowerCase();
          let genres = document.querySelectorAll('.dropdown ul li');
      
          genres.forEach(genre => {
              let text = genre.textContent.toLowerCase();
              if (text.includes(input)) {
                  genre.style.display = "block";
              } else {
                  genre.style.display = "none";
              }
          });
      }
      </script>
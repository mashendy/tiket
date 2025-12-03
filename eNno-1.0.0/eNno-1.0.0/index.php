<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Dashboard | Web Dinamis</title>
  <meta name="description" content="Dynamic Web Dashboard" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Sidebar/Menu -->
     
      <!-- /Sidebar/Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar (optional, bisa tambah sendiri) -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Main Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <?php
            if (isset($_GET['page'])) {
              $page = $_GET['page'];

              switch ($page) {
                case 'admin':
                  include "admin.php";
                  break;
                case 'member':
                  include "member.php";
                  break;
                case 'acara':
                  include "acara.php";
                  break;
                case 'beli':
                  include "beli.php";
                  break;
                case 'profil':
                  include "profil.php";
                  break;
                case 'petugas':
                  include "petugas.php";
                  break;
                case 'pembayaran':
                  include "pembayaran.php";
                  break;
                case 'contact':
                  include "contact.php";
                  break;
                case 'masyarakat':
                  include "masyarakat.php";
                  break;
                default:
                  echo "<div class='alert alert-danger'>Halaman tidak ditemukan!</div>";
                  break;
              }
            } else {
              include "home.php"; // Default home
            }
            ?>
          </div>
          <!-- /Main Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex justify-content-between py-2 flex-md-row flex-column">
              <div>
                © <script>document.write(new Date().getFullYear())</script>, made with ❤️ by
                <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
              </div>
              <div>
                <a href="#" class="footer-link me-4">License</a>
                <a href="#" class="footer-link me-4">Documentation</a>
                <a href="#" class="footer-link me-4">Support</a>
              </div>
            </div>
          </footer>
          <!-- /Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- /Content wrapper -->
      </div>
      <!-- /Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- /Layout wrapper -->

  <!-- Core JS -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

</body>
</html>

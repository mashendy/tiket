<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>Register - Cassey Cars</title>

  <!-- Bootstrap CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700" rel="stylesheet" />

  <!-- Custom CSS -->
  <style>
    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      font-family: 'Nunito', sans-serif;
    }

    @keyframes gradientBG {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      background: rgba(255, 255, 255, 0.9);
    }

    .card-title {
      font-weight: 700;
      color: #333;
    }

    .form-control {
      border-radius: 10px;
      transition: 0.3s;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      border-color: #007bff;
    }

    .btn-primary {
      background: linear-gradient(90deg, #007bff, #00c6ff);
      border: none;
      font-weight: 600;
      border-radius: 25px;
      transition: background 0.4s ease-in-out;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #00c6ff, #007bff);
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #555;
    }

    .small a {
      color: #007bff;
      text-decoration: none;
      font-weight: 600;
    }

    .small a:hover {
      text-decoration: underline;
    }

    .logo img {
      height: 40px;
      margin-right: 8px;
    }

    .logo span {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
    }

    .credits {
      font-size: 0.75rem;
      color: #ddd;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo_h-removebg-preview.png" alt="" />
                  <span class="d-none d-lg-block">Tiketku</span>
                </a>
              </div>

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your details to register</p>
                  </div>

                  <form id="formRegister" class="row g-3" action="register_proses.php" method="POST" novalidate>

                    <div class="col-12">
                      <label for="nama" class="form-label">Full Name</label>
                      <input type="text" name="nama" class="form-control" id="nama" placeholder="Your full name" required />
                      <div class="invalid-feedback">Please enter your full name.</div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required />
                      <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required minlength="6" />
                      <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>

                    <div class="col-12">
                      <label for="password_confirm" class="form-label">Confirm Password</label>
                      <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Confirm password" required />
                      <div class="invalid-feedback" id="confirmPasswordFeedback">Please confirm your password.</div>
                    </div>

                    <div class="col-12">
                    <label for="no_hp" class="form-label">Phone Number</label>
                     <input type="text" name="no_hp" class="form-control" id="no_hp" placeholder="08xxxxxxx" required />
                     <div class="invalid-feedback">Please enter your phone number.</div>
                    </div>

                    <div class="col-12">
                    <label for="alamat" class="form-label">Address</label>
                    <textarea name="alamat" class="form-control" id="alamat" rows="3" placeholder="Your address" required></textarea>
                    <div class="invalid-feedback">Please enter your address.</div>
                    </div>


                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Register</button>
                    </div>

                    <div class="col-12 text-center">
                      <p class="small mb-0">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Bootstrap Bundle JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Client side validation -->
  <script>
    (() => {
      'use strict'
      const form = document.getElementById('formRegister');
      const password = document.getElementById('password');
      const passwordConfirm = document.getElementById('password_confirm');
      const confirmFeedback = document.getElementById('confirmPasswordFeedback');

      form.addEventListener('submit', event => {
        if (!form.checkValidity() || password.value !== passwordConfirm.value) {
          event.preventDefault();
          event.stopPropagation();

          if (password.value !== passwordConfirm.value) {
            passwordConfirm.classList.add('is-invalid');
            confirmFeedback.textContent = "Passwords do not match.";
          } else {
            passwordConfirm.classList.remove('is-invalid');
          }

          form.classList.add('was-validated');
        }
      });

      passwordConfirm.addEventListener('input', () => {
        if (passwordConfirm.value === password.value) {
          passwordConfirm.classList.remove('is-invalid');
        }
      });
    })();
  </script>

  <!-- Alert error -->
  <?php if (isset($_SESSION['error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        text: '<?= $_SESSION['error']; ?>',
        confirmButtonColor: '#d33'
      });
    </script>
  <?php unset($_SESSION['error']);
  endif; ?>

  <!-- Alert success -->
  <?php if (isset($_SESSION['success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?= $_SESSION['success']; ?>',
        confirmButtonColor: '#3085d6'
      });
    </script>
  <?php unset($_SESSION['success']);
  endif; ?>

</body>

</html>

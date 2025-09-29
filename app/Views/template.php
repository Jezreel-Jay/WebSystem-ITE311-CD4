<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MyWeb</title>

  <!-- Bootstrap CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f5f5f5;
      font-family: "Poppins", sans-serif;
      color: #000;
    }
    .navbar { background-color: #003366; }
    .navbar-brand { font-weight: bold; font-size: 1.3rem; color: #fff !important; }
    .nav-link { color: #fff !important; margin: 0 8px; transition: 0.3s ease; }
    .nav-link:hover, .nav-link.active { color: #121212 !important; font-weight: bold; }
    .container { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0px 2px 6px rgba(0,0,0,0.6); }
  </style>
</head>
<body>
  <?php 
    $session = session(); 
    $uri = uri_string();
  ?>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= site_url('/') ?>">MyWeb</a>
      <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarNav" aria-controls="navbarNav" 
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

          <?php if (!$session->get('isLoggedIn')): ?>
            <!-- Show Home/About/Contact ONLY when not logged in -->
            <li class="nav-item">
              <a class="nav-link <?= $uri == '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $uri == 'about' ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $uri == 'contact' ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a>
            </li>

            <!-- Login/Register -->
            <li class="nav-item">
              <a class="nav-link <?= $uri == 'login' ? 'active' : '' ?>" href="<?= site_url('login') ?>">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $uri == 'register' ? 'active' : '' ?>" href="<?= site_url('register') ?>">Register</a>
            </li>

          <?php else: ?>
            <!-- Role-specific links (only visible when logged in) -->
            <?php if ($session->get('role') === 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link <?= $uri == 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">Admin Panel</a>
              </li>
            <?php elseif ($session->get('role') === 'teacher'): ?>
              <li class="nav-item">
                <a class="nav-link <?= $uri == 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">My Classes</a>
              </li>
            <?php elseif ($session->get('role') === 'student'): ?>
              <li class="nav-item">
                <a class="nav-link <?= $uri == 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">My Subjects</a>
              </li>
            <?php endif; ?>

            <!-- Logout -->
            <li class="nav-item">
              <a class="nav-link text-danger" href="<?= site_url('logout') ?>">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content Section -->
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>
  </body>
</html>


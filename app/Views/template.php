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

    /* Navbar Styling */
    .navbar {
      background-color: #003366;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.3rem;
      color: #fff !important;
    }
    .nav-link {
      color: #fff !important;
      margin: 0 8px;
      transition: 0.3s ease;
    }
    .nav-link:hover {
      color: #121212 !important;
    }
    .nav-link.active {
      color: #121212 !important;
      font-weight: bold;
    }

    /* Content Styling */
    .container {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0px 2px 6px rgba(0,0,0,0.6);
    }
   

  </style>
</head>
<body>

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
          <li class="nav-item">
            <a class="nav-link <?= uri_string() == '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'about' ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'contact' ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="container mt-4">
    <!-- Dynamic page content -->
    <?= $this->renderSection('content') ?>
  </div>

</body>
</html>


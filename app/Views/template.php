<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background: #f5f5f5; font-family: "Poppins", sans-serif; color: #000; }
        .container { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.6); }

        .navbar .nav-link { color: white !important; transition: 0.3s; }
        .navbar .nav-link:hover { color: #cccccc !important; }
        .navbar .nav-link.active-link { color: black !important; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Include role-based header -->
    <?= $this->include('templates/header') ?>

      <!-- Page content -->
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>

</body>

</html>

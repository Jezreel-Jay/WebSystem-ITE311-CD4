<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { 
            background: #f5f5f5; 
            font-family: "Poppins", sans-serif; 
            color: #000; 
        }

        /* Base container for all pages */
        .main-container {
            background: #fff; 
            border-radius: 10px; 
            padding: 30px; 
            margin: 10px auto;        
            box-shadow: 0 2px 6px rgba(0,0,0,0.6);
        }

        /* Dashboard layout: wide & full height */
        .wide-container .main-container {
            max-width: 1600px;
            min-height: calc(100vh - 80px);
        }

        /* Home/About/Contact: narrower & auto height */
        .narrow-container .main-container {
            max-width: 900px;
            min-height: auto;
        }

        .navbar .nav-link { 
            color: white !important; 
            transition: 0.3s; 
        }
        .navbar .nav-link:hover { 
            color: #cccccc !important; 
        }
        .navbar .nav-link.active-link { 
            color: black !important; 
            font-weight: bold; 
        }

        .dashboard-card { margin-bottom: 20px; }
    </style>
</head>
<body class="<?php 
    $uri = uri_string();
    // Only Home, About, Contact use narrow container
    if (in_array($uri, ['', 'home', 'about', 'contact'])) {
        echo 'narrow-container';
    } else {
        echo 'wide-container';
    }
?>">

    <?= $this->include('templates/header') ?>

    <div class="main-container">
        <?= $this->renderSection('content') ?>
    </div>

</body>
</html>

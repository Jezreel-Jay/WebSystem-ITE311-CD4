<?php
$session = session();
$uri = uri_string();
?>
<nav class="navbar navbar-expand-lg" style="background-color: #003366;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="<?= base_url('/') ?>">MyWeb</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (!$session->get('isLoggedIn')): ?>
                    <li class="nav-item"><a class="nav-link <?= $uri === '' ? 'active-link' : '' ?>" href="<?= base_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'about' ? 'active-link' : '' ?>" href="<?= base_url('about') ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'contact' ? 'active-link' : '' ?>" href="<?= base_url('contact') ?>">Contact</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'register' ? 'active-link' : '' ?>" href="<?= base_url('register') ?>">Register</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'login' ? 'active-link' : '' ?>" href="<?= base_url('login') ?>">Login</a></li>

                <?php else: ?>
                    <?php
                    $role = $session->get('role');
                    $links = [];

                    if ($role === 'admin') {
                        $links = [
                            'dashboard' => 'Dashboard',
                            'manage-users' => 'Manage Users'
                        ];
                    } elseif ($role === 'teacher') {
                        $links = ['dashboard' => 'Dashboard'];
                    } elseif ($role === 'student') {
                        $links = ['dashboard' => 'Dashboard'];
                    }
                    ?>

                    <?php foreach ($links as $path => $label): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= ($uri === $path) ? 'active-link' : '' ?>" 
                               href="<?= base_url($path) ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <!-- ===== NEW: Account Dropdown ===== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-white" 
                           href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="gap: 6px;">
                            <!-- Small Default Profile Icon -->
                            <i class="bi bi-person-circle fs-5" style="color: gray;"></i>
                            Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>" 
                                   onclick="return confirm('Are you sure you want to log out?');">
                                   <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- ===== END Account Dropdown ===== -->

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

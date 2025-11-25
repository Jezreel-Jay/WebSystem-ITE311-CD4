<?php
$session = session();
$uri = uri_string();
?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="<?= base_url('/') ?>">MyWeb</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (!$session->get('isLoggedIn')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $uri === '' ? 'active-link' : '' ?>" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $uri === 'about' ? 'active-link' : '' ?>" href="<?= base_url('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $uri === 'contact' ? 'active-link' : '' ?>" href="<?= base_url('contact') ?>">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $uri === 'register' ? 'active-link' : '' ?>" href="<?= base_url('register') ?>">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $uri === 'login' ? 'active-link' : '' ?>" href="<?= base_url('login') ?>">Login</a>
                    </li>

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

                    if ($uri === 'restricted-users') {
                        $uri = 'manage-users';
                    }
                    ?>

                    <?php foreach ($links as $path => $label): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($uri === $path) ? 'active-link' : '' ?>" 
                               href="<?= base_url($path) ?>">
                               <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <!-- ===== ACCOUNT DROPDOWN ===== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" 
                           href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="gap: 6px;">
                            <i class="bi bi-person-circle fs-5 text-secondary"></i>
                            Account
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('settings') ?>">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger d-flex align-items-center" href="<?= base_url('logout') ?>" 
                                   onclick="return confirm('Are you sure you want to log out?');">
                                   <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- ===== END ACCOUNT DROPDOWN ===== -->

                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ==================== NAVBAR CUSTOM STYLES ==================== -->
<style>
    nav.navbar {
        background: linear-gradient(90deg, #1e3c72, #2a5298);
    }

    nav.navbar .nav-link {
        color: #e0e0e0 !important;
        transition: 0.3s;
    }

    nav.navbar .nav-link:hover {
        color: #ffd700 !important;
    }

    nav.navbar .nav-link.active-link {
        color: #ffda6b !important;
        font-weight: bold;
    }

    nav.navbar .dropdown-menu {
        background-color: #1e3c72;
        border: none;
    }

    nav.navbar .dropdown-item {
        color: #e0e0e0;
    }

    nav.navbar .dropdown-item:hover {
        background-color: #2a5298;
        color: #ffd700;
    }

    nav.navbar .dropdown-item.text-danger:hover {
        background-color: #e2cfd0ff;
        color: #fff;
    }

    nav.navbar .navbar-toggler-icon {
        filter: invert(1);
    }
</style>

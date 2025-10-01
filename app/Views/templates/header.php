<?php
$session = session();
$uri = uri_string(); // e.g., 'dashboard', 'my-classes', 'login'
?>
<nav class="navbar navbar-expand-lg" style="background-color: #003366;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="<?= base_url('/') ?>">MyWeb</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (!$session->get('isLoggedIn')): ?>
                    <li class="nav-item"><a class="nav-link <?= $uri === '' ? 'active-link' : '' ?>" href="<?= base_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'about' ? 'active-link' : '' ?>" href="<?= base_url('about') ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'contact' ? 'active-link' : '' ?>" href="<?= base_url('contact') ?>">Contact</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'login' ? 'active-link' : '' ?>" href="<?= base_url('login') ?>">Login</a></li>
                    <li class="nav-item"><a class="nav-link <?= $uri === 'register' ? 'active-link' : '' ?>" href="<?= base_url('register') ?>">Register</a></li>

                <?php else: ?>
                    <?php
                    // Define role links
                    $role = $session->get('role');
                    $links = [];
                    if ($role === 'admin') {
                        $links = [
                            'dashboard' => 'Dashboard',
                            //'manage-users' => 'Manage Users',
                            //'settings' => 'Settings'
                        ];
                    } elseif ($role === 'teacher') {
                        $links = [
                            'dashboard' => 'Dashboard',
                            'my-classes' => 'My Classes',
                            'grades' => 'Grades'
                        ];
                    } elseif ($role === 'student') {
                        $links = [
                            'dashboard' => 'Dashboard',
                            'subjects' => 'Subjects',
                            'assignments' => 'Assignments'
                        ];
                    }
                     if ($role === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= (strpos($uri, 'manage-users') !== false) ? 'active-link' : '' ?>" 
                            href="#" id="manageUsersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage Users
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('dashboard#users') ?>">View All Users</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard#add-user') ?>">Add User</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard#roles') ?>">Roles & Permissions</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                   <?php foreach ($links as $path => $label):
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($uri === $path) ? 'active-link' : '' ?>" href="<?= base_url($path) ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach; ?>

                    <li class="nav-item">
                        <a 
                            class="nav-link text-danger" 
                            href="<?= base_url('logout') ?>" 
                            onclick="return confirm('Are you sure you want to log out?');"
                        >
                            Logout
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">My App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Always visible -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>

                <!-- 🔹 Role-specific items -->
                <?php if (session('role') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('users') ?>">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('reports') ?>">Reports</a>
                    </li>

                <?php elseif (session('role') === 'teacher'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('classes') ?>">My Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('grades') ?>">Grades</a>
                    </li>

                <?php elseif (session('role') === 'student'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('subjects') ?>">Subjects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('assignments') ?>">Assignments</a>
                    </li>
                <?php endif; ?>

                <!-- Always visible logout -->
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

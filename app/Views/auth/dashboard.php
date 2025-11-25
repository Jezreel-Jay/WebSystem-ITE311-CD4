<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<!-- WRAPPED WELCOME SECTION IN TRANSPARENT OUTLINE -->
<div class="welcome-box p-4 mb-3">
    <div class="d-flex align-items-center">
        <!-- Default Gray Profile Icon -->
        <i class="bi bi-person-circle me-3" style="font-size: 80px; color: gray;"></i>

        <!-- Name and Email -->
        <div>
            <h1 class="mb-1">Welcome, <?= ucfirst(esc($name)) ?>!</h1>
            <p class="mb-0"><i class="bi bi-envelope-fill me-2"></i><?= esc($email) ?></p>
        </div>
    </div>
</div>


<?php if ($role === 'admin'): ?>
    <h2 class="mb-4 text-primary fw-bold">Admin Dashboard</h2>

    <!--  FLASH NOTIFICATIONS -->
    <?php if (session()->getFlashdata('add_success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('add_success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('add_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('add_error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <!--  END FLASH -->

    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>All Users</h5>
                    <p class="card-text display-4"><?= esc($currentUsers) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-shield-lock-fill me-2"></i>Admins</h5>
                    <p class="card-text display-4"><?= esc($admins) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-badge-fill me-2"></i>Teachers</h5>
                    <p class="card-text display-4"><?= esc($teachers) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-mortarboard-fill me-2"></i>Students</h5>
                    <p class="card-text display-4"><?= esc($students) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!--  User List Table -->
    <div class="card mt-5 shadow-sm border-0 userlist-wrapper">
    
        <div class="card-header d-flex align-items-center" style="background-color: #003366; color: white;">
            <i class="bi bi-list-ul fs-4 me-2"></i>
            <h4 class="mb-0">User List</h4>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead style="background-color: #0d47a1; color: white;">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 200px;">Name</th>
                        <th style="width: 250px;">Email</th>
                        <th style="width: 150px;">Role</th>
                        <th style="width: 180px;">Created At</th>
                        <th style="width: 180px;">Updated At</th>
                        <th style="width: 150px;" class="text-center">Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($allUsers)): ?>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $user['role'] === 'admin' ? 'bg-danger' : 
                                           ($user['role'] === 'teacher' ? 'bg-success' : 'bg-info') ?>">
                                        <?= ucfirst(esc($user['role'])) ?>
                                    </span>
                                </td>
                                    <td><?= esc($user['created_at']) ?></td>
                                    <td><?= esc($user['updated_at']) ?></td>
                                <!-- STATUS COLUMN -->
                                <td class="text-center">

                                    <?php if ($user['status'] === 'active'): ?>
                                        <span class="px-3 py-1 bg-primary text-white">Active</span>

                                    <?php elseif ($user['status'] === 'restricted'): ?>
                                        <span class="px-3 py-1 bg-secondary text-white">Restricted</span>

                                    <?php elseif ($user['status'] === 'deleted'): ?>
                                        <span class="px-3 py-1 bg-dark text-white">Deleted</span>

                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-light text-dark">Unknown</span>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No users found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    


<?php elseif ($role === 'teacher'): ?>
    <!--  TEACHER DASHBOARD -->
    <h2 class="mb-4 text-success fw-bold">Teacher Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-journal-text me-2"></i>My Courses</h5>
                    <p class="card-text display-4"><?= esc($myCourses) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>My Students</h5>
                    <p class="card-text display-4"><?= esc($myStudents) ?></p>
                </div>
            </div>
        </div>
    </div>


<?php elseif ($role === 'student'): ?>
    <!-- 🎓 STUDENT DASHBOARD -->
    <h2 class="mb-4 text-info fw-bold">Student Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-book-fill me-2"></i>Enrolled Courses</h5>
                    <p class="card-text display-4"><?= esc($enrolledCourses) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-check-circle-fill me-2"></i>Completed Lessons</h5>
                    <p class="card-text display-4"><?= esc($completedLessons) ?></p>
                </div>
            </div>
        </div>
    </div>


<?php else: ?>
    <div class="alert alert-warning">
        Unknown role. Please contact the administrator.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<!-- Auto-hide alerts -->
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => a.classList.add('fade'));
    }, 4000);
</script>

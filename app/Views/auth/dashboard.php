

<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h1>Welcome, <?= esc($name) ?>!</h1>
<p>Email: <?= esc($email) ?></p>

<div class="row mt-4">

<?php if ($role === 'admin'): ?>
    <!-- ADMIN DASHBOARD -->
    <h2 class="mb-4 text-primary fw-bold">Admin Dashboard</h2>
    <div id="users" class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="bi bi-people-fill me-2"></i>All Users
                </h5>
                <p class="card-text display-4"><?= esc($currentUsers) ?></p>
            </div>
        </div>
    </div>

    <div id="roles" class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="bi bi-shield-lock-fill me-2"></i>Admins
                </h5>
                <p class="card-text display-4"><?= esc($admins) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="bi bi-person-badge-fill me-2"></i>Teachers
                </h5>
                <p class="card-text display-4"><?= esc($teachers) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="bi bi-mortarboard-fill me-2"></i>Students
                </h5>
                <p class="card-text display-4"><?= esc($students) ?></p>
            </div>
        </div>
    </div>


<div class="mt-5" id="add-user">
    <h3 class="mb-3" style="color:#008080;">Add New User</h3>

    <?php if (session()->getFlashdata('add_success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('add_success')) ?></div>
    <?php elseif (session()->getFlashdata('add_error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('add_error')) ?></div>
    <?php endif; ?>

    <!-- Horizontal Form Container -->
    <div class="border p-4 bg-white shadow-sm" 
     style="border-radius:10px; width:100%; display:flex; flex-wrap:wrap; align-items:flex-end; gap:15px;">
        <form action="<?= base_url('auth/addUserByAdmin') ?>" method="post" class="d-flex flex-wrap gap-3 align-items-end w-100">

            <div style="flex:1 1 150px;">
                <label for="name" class="form-label fw-bold">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       placeholder="Enter name" required
                       value="<?= old('name', '', false) ?>">
            </div>

            <div style="flex:1 1 150px;">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="Enter email" required
                       value="<?= old('email', '', false) ?>">
            </div>

            <div style="flex:1 1 120px;">
                <label for="password" class="form-label fw-bold">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Password" required>
            </div>

            <div style="flex:1 1 120px;">
                <label for="password_confirm" class="form-label fw-bold">Confirm</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                       placeholder="Confirm" required>
            </div>

            <div style="flex:1 1 130px;">
                <label for="role" class="form-label fw-bold">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <div style="flex:0 0 auto;">
                <button type="submit" class="btn btn-primary px-4">Add</button>
            </div>
        </form>
    </div>
</div>




<?php elseif ($role === 'teacher'): ?>
    <!-- TEACHER DASHBOARD -->
    <h2 class="mb-4 text-success fw-bold">Teacher Dashboard</h2>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">My Courses</h5>
                <p class="card-text display-4 text-center"><?= esc($myCourses) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">My Students</h5>
                <p class="card-text display-4 text-center"><?= esc($myStudents) ?></p>
            </div>
        </div>
    </div>

<?php elseif ($role === 'student'): ?>
    <!-- STUDENT DASHBOARD -->
    <h2 class="mb-4 text-info fw-bold">Student Dashboard</h2>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Enrolled Courses</h5>
                <p class="card-text display-4 text-center"><?= esc($enrolledCourses) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Completed Lessons</h5>
                <p class="card-text display-4 text-center"><?= esc($completedLessons) ?></p>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="col-12">
        <div class="alert alert-warning">
            Unknown role. Please contact the administrator.
        </div>
    </div>
<?php endif; ?>

</div> 

<?php if ($role === 'admin' && !empty($allUsers)): ?>
<div id="manage-users" class="mt-5">
    <h3 class="mb-3" style="color:#4169E1;">Manage Users</h3>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 190px;">Name</th>
                <th style="width: 250px;">Email</th>
                <th style="width: 140px;">Role</th>
                <th style="width: 260px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allUsers as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>

                    <td class="text-center">
                        <?php $masterAdminId = 1; ?>
                        <?php if ($user['id'] != $masterAdminId): ?>
                            <span class="badge bg-secondary"><?= ucfirst(esc($user['role'])) ?></span>
                        <?php else: ?>
                            <span class="text-muted">Admin (Protected)</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-end">
                        <?php if ($user['id'] != $masterAdminId): ?>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <!-- Update Role Form -->
                                <form action="<?= base_url('auth/updateUserRole') ?>" method="post" class="d-flex align-items-center gap-2 m-0">
                                    <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                                    <select name="role" class="form-select form-select-sm" style="width:110px;">
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </form>

                                <!-- Delete Form -->
                                <form action="<?= base_url('auth/deleteUser') ?>" method="post"
                                      onsubmit="return confirm('Are you sure you want to delete this user?')" class="m-0">
                                    <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Protected</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>



<?= $this->endSection() ?>


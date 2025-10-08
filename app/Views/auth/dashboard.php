<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h1>Welcome, <?= esc($name) ?>!</h1>
<p>Email: <?= esc($email) ?></p>

<div class="row mt-4">

<?php if ($role === 'admin'): ?>
    <!-- ADMIN DASHBOARD -->
    <div id="users" class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">All Users</h5>
                <p class="card-text display-4 text-center"><?= esc($currentUsers) ?></p>
            </div>
        </div>
    </div>

    <div id="roles" class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Admins</h5>
                <p class="card-text display-4 text-center"><?= esc($admins) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Teachers</h5>
                <p class="card-text display-4 text-center"><?= esc($teachers) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Students</h5>
                <p class="card-text display-4 text-center"><?= esc($students) ?></p>
            </div>
        </div>
    </div>

<div class="mt-5" id="add-user">
    <h3 class="mb-3">Add New User</h3>

    <?php if (session()->getFlashdata('add_success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('add_success')) ?></div>
    <?php elseif (session()->getFlashdata('add_error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('add_error')) ?></div>
    <?php endif; ?>

    <!-- Horizontal Form Container -->
    <div class="border p-4 bg-white shadow-sm d-flex flex-wrap align-items-end gap-3" style="border-radius:10px;">
        <form action="<?= base_url('auth/addUserByAdmin') ?>" method="post" class="d-flex flex-wrap gap-3 align-items-end">

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
    <h3 class="mb-3">Manage Users</h3>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th style="width: 160px;">Role</th>
                <th style="width: 130px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allUsers as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td class="p-1">
                        <?php $masterAdminId = 1; ?>
                        <?php if ($user['id'] != $masterAdminId): ?>
                        <!-- Inline Role Edit Form -->
                        <form action="<?= base_url('auth/updateUserRole') ?>" method="post" class="d-flex align-items-center" style="gap:4px;">
                            <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                            <select name="role" class="form-select form-select-sm" style="width:110px;">
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success px-2">✓</button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">Admin (Protected)</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-1">
                        <?php if ($user['id'] != $masterAdminId): ?>
                            <form action="<?= base_url('auth/deleteUser') ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?')" style="display:inline;">
                                <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                                <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                            </form>
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

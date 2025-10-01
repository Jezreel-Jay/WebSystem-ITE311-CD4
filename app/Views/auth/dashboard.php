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

</div> <!-- end .row -->

<!-- ADMIN TABLE + BUTTONS -->
<?php if ($role === 'admin' && !empty($allUsers)): ?>
<div id="add-user" class="mt-5">
    <h3>All Users</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allUsers as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><?= esc($user['role']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mt-3">
        <button class="btn btn-primary btn-sm">+ Add User (demo)</button>
        <button class="btn btn-secondary btn-sm">Manage Roles (demo)</button>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

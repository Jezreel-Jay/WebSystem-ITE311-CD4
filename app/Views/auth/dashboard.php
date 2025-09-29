<?= $this->extend('template') ?>
<?= $this->section('content') ?>


<h1>Welcome, <?= esc($name) ?>!</h1>
<p>Email: <?= esc($email) ?></p>

<?php if ($role === 'admin'): ?>
    <h2>Admin Dashboard</h2>
    <p>Manage users, roles, and system settings here.</p>

<?php elseif ($role === 'teacher'): ?>
    <h2>Teacher Dashboard</h2>
    <p>Manage your classes, students, and grades.</p>

<?php elseif ($role === 'student'): ?>
    <h2>Student Dashboard</h2>
    <p>View your subjects, assignments, and grades.</p>

<?php else: ?>
    <h2>Unknown Role</h2>
    <p>Please contact the administrator.</p>
<?php endif; ?>

<?= $this->endSection() ?>

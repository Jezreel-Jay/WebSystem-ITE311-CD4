<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h2 class="text-primary fw-bold mb-4 d-flex justify-content-between align-items-center">
    <div>
        <i class="bi bi-gear-fill me-2"></i>Manage Users
    </div>
    <div class="d-flex gap-2">
        <!-- Add New User Button -->
        <button class="btn btn-add-user" id="toggleAddUser">
            <i class="bi bi-person-plus-fill"></i> Add New User
        </button>

        <!-- View Restricted Users Button -->
        <a href="<?= base_url('restricted-users') ?>" class="btn btn-secondary">
            <i class="bi bi-eye-fill"></i> View Restricted Users
        </a>
    </div>
</h2>

<!-- ADD NEW USER FORM -->
<?php 
$showAddForm = session()->getFlashdata('add_error') ? 'block' : 'none';
?>
<div id="addUserForm" class="card mb-4 shadow-sm border-0" style="display:<?= $showAddForm ?>;">
    <div class="card-header d-flex align-items-center" style="background-color:#1343b3; color:white;">
        <i class="bi bi-person-plus-fill fs-5 me-2"></i>
        <h5 class="mb-0">Add New User</h5>
    </div>
    <div class="card-body">
        <!--  Add User Alerts -->
        <?php if (session()->getFlashdata('add_error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= esc(session()->getFlashdata('add_error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('add_success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('add_success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/addUserByAdmin') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Full Name" required
                       value="<?= old('name') ?>"
                       pattern="^[a-zA-Z\s'.-]+$"
                       title="Letters, spaces, period, dash, and apostrophe only.">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required
                       value="<?= old('email') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Confirm</label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirm" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select</option>
                    <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                </select>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-add-user-submit px-4">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- USER LIST -->
<?php if ($role === 'admin' && !empty($allUsers)): ?>
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header d-flex align-items-center" style="background-color:#003366; color:white;">
        <i class="bi bi-list-ul fs-5 me-2"></i>
        <h5 class="mb-0">User List</h5>
    </div>

    <!--  User List Alerts -->
    <div class="p-3">
        <?php if (session()->getFlashdata('success') || session()->getFlashdata('add_success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('success') ?? session()->getFlashdata('add_success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-body pt-0">
        <table class="table table-bordered table-striped align-middle">
            <thead style="background-color:#0d47a1; color:white;">
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:180px;">Name</th>
                    <th style="width:240px;">Email</th>
                    <th style="width:130px;">Role</th>
                    <th style="width:200px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $masterAdminId = 1; ?>
                <?php foreach ($allUsers as $user): ?>
                    <?php if ($user['status'] !== 'restricted'): ?>
                    <tr>
                        <td><?= esc($user['id']) ?></td>
                        <td><?= esc($user['name']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td>
                            <?php if ($user['id'] != $masterAdminId): ?>
                                <span class="badge 
                                    <?= $user['role'] === 'admin' ? 'bg-danger' : 
                                        ($user['role'] === 'teacher' ? 'bg-success' : 'bg-info') ?>">
                                    <?= ucfirst(esc($user['role'])) ?>
                                </span>
                            <?php else: ?>
                                    <span class="badge bg-danger">
                                    <i class="bi bi-shield-lock-fill me-1"></i>Admin (you)
                                </span>
                            <?php endif; ?>
                        </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-2">

                                        <!-- 🔹 Edit Button for all users except master admin ID 1 can still be applied -->
                                        <?php if ($user['role'] === 'admin' || $user['id'] == $masterAdminId || $user['role'] !== 'admin'): ?>
                                            <button 
                                                class="btn btn-sm btn-warning btn-edit-user"
                                                data-id="<?= esc($user['id']) ?>"
                                                data-name="<?= esc($user['name']) ?>"
                                                data-email="<?= esc($user['email']) ?>"
                                                data-role="<?= esc($user['role']) ?>">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                        <?php endif; ?>

                                        <!--  Restrict Button (only for non-admins/non-master) -->
                                        <?php if ($user['id'] != $masterAdminId && $user['role'] !== 'admin'): ?>
                                            <form action="<?= base_url('restrictUser') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-secondary" style="min-width:110px;"
                                                    onclick="return confirm('Restrict this user?')">
                                                    <i class="bi bi-lock"></i> Restrict
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                    <span class="badge bg-dark px-3 py-2" style="min-width:110px; text-align:center;">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Protected
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Toggle Add User Form -->
<script>
document.getElementById('toggleAddUser').addEventListener('click', function() {
    const form = document.getElementById('addUserForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});
</script>

<?= $this->endSection() ?>

<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h2 class="text-primary fw-bold mb-4">
    <i class="bi bi-gear-fill me-2"></i>Manage Users
</h2>

<!-- ✅ ADD NEW USER -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header d-flex align-items-center" style="background-color:#006400; color:white;">
        <i class="bi bi-person-plus-fill fs-5 me-2"></i>
        <h5 class="mb-0">Add New User</h5>
    </div>
    <div class="card-body">

        <!-- 🔔 Flash Messages (Updated: Stay on same page, no redirect) -->
        <?php if (session()->getFlashdata('add_success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('add_success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('add_error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= esc(session()->getFlashdata('add_error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/addUserByAdmin') ?>" method="post" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
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
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success px-4">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ MANAGE USER LIST -->
<?php if ($role === 'admin' && !empty($allUsers)): ?>
<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center" style="background-color:#003366; color:white;">
        <i class="bi bi-list-ul fs-5 me-2"></i>
        <h5 class="mb-0">User List</h5>
    </div>
    <div class="card-body">

        <!-- 🔔 Flash Messages for Updates/Deletes -->
        <?php if (session()->getFlashdata('success')): ?>
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

        <table class="table table-bordered table-striped align-middle">
            <thead style="background-color:#0d47a1; color:white;">
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:180px;">Name</th>
                    <th style="width:240px;">Email</th>
                    <th style="width:130px;">Role</th>
                    <th style="width:260px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $masterAdminId = 1; ?>
                <?php foreach ($allUsers as $user): ?>
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
                                <span class="text-muted">Admin (Protected)</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if ($user['id'] != $masterAdminId): ?>
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <!-- 🔄 Update Role -->
                                    <form action="<?= base_url('auth/updateUserRole') ?>" method="post" class="d-flex gap-2 m-0">
                                        <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
                                        <select name="role" class="form-select form-select-sm" style="width:110px;">
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                            <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                    </form>

                                    <!-- ❌ Delete -->
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
</div>
<?php endif; ?>

<?= $this->endSection() ?>

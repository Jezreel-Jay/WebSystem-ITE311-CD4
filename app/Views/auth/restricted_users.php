<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h2 class="text-primary fw-bold mb-4 d-flex justify-content-between align-items-center">
    <div>
        <i class="bi bi-lock-fill me-2"></i>Restricted Users
    </div>
    <a href="<?= base_url('manage-users') ?>" class="btn btn-dark">
        <i class="bi bi-arrow-left-circle"></i> Back
    </a>
</h2>

<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center" style="background-color:#4B0082; color:white;">
        <i class="bi bi-exclamation-circle fs-5 me-2"></i>
        <h5 class="mb-0">Restricted User List</h5>
    </div>
    <div class="card-body">
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
            <thead style="background-color:#a52a2a; color:white;">
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:200px;">Name</th>
                    <th style="width:240px;">Email</th>
                    <th style="width:130px;">Role</th>
                    <th style="width:240px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restrictedUsers as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst(esc($user['role'])) ?></span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <!--  UNRESTRICT -->
                            <form action="<?= base_url('auth/unrestrictUser') ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('Unrestrict this user?')">
                                    <i class="bi bi-unlock"></i> Unrestrict
                                </button>
                            </form>

                            <!--  DELETE PERMANENTLY -->
                            <form action="<?= base_url('auth/deleteUserPermanent') ?>" method="post" class="d-inline"
                                  onsubmit="return confirm('Delete this user permanently? This cannot be undone!')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i> Delete Permanently
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

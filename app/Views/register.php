<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center mt-2">
    <div class="col-md-6 col-lg-5">
        <h1 class="text-center mb-4" style="color:#000;">Create Account</h1>

        <?php if (session()->getFlashdata('register_error')): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= esc(session()->getFlashdata('register_error')) ?>
            </div>
        <?php endif; ?>

        <div class="card border-0" style="background:#003366; box-shadow:0px 3px 10px rgba(0,0,0,0.6); border-radius:12px;">
            <div class="card-body p-4">
                <form action="<?= base_url('register') ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Name</label>
                        <input type="text" class="form-control bg-white text-dark border-secondary" 
                            id="name" name="name" required value="<?= old('name') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control bg-white text-dark border-secondary" 
                            id="email" name="email" required value="<?= old('email') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Password</label>
                        <input type="password" class="form-control bg-white text-dark border-secondary" 
                               id="password" name="password" required>
                    </diregv>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label text-white">Confirm Password</label>
                        <input type="password" class="form-control bg-white text-dark border-secondary" 
                               id="password_confirm" name="password_confirm" required>
                    </div>
<!-- 
                    <div class="mb-3">
                        <label for="role" class="form-label text-white">Role</label>
                        <select class="form-control bg-white text-dark border-secondary" 
                                id="role" name="role" required>
                            <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select> -->
                    </div>
                                        
                        <button type="submit" 
                                class="btn" 
                                style="background:#002147; color:#fff; border-radius:20px; font-weight:500; transition:0.3s; padding:10px 30px; display:block; margin:0 auto;">
                            Register
                        </button>
                </form>

                <!-- Already have an account? -->
                <div class="text-center mt-3">
                    <p class="mb-0 text-white">
                        Already have an account? 
                        <a href="<?= base_url('login') ?>" style="color:#1E90FF;">Log in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

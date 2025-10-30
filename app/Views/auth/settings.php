<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<h2 class="text-primary fw-bold mb-4"><i class="bi bi-gear-fill me-2"></i>Settings</h2>

<div class="card p-4 shadow-sm border-0" style="max-width: 400px;">
    <h5>Appearance</h5>
    <div class="form-check form-switch mt-3">
        <input class="form-check-input" type="checkbox" id="darkModeToggleSettings">
        <label class="form-check-label" for="darkModeToggleSettings">Dark Mode</label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const toggle = document.getElementById('darkModeToggleSettings');

    // Load saved mode from localStorage
    if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('dark-mode');
        if (toggle) toggle.checked = true;
    }

    // Toggle Dark Mode
    if (toggle) {
        toggle.addEventListener('change', function () {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        });
    }
});
</script>

<?= $this->endSection() ?>

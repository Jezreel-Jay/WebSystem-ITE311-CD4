<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { 
            background: #f5f5f5; 
            font-family: "Poppins", sans-serif; 
            color: #000; 
            transition: all 0.3s ease-in-out;
        }

        .main-container {
            background: #e0f7ff; 
            border-radius: 10px; 
            padding: 30px; 
            margin: 10px auto;        
            box-shadow: 0 2px 6px rgba(0,0,0,0.6);
        }

        .wide-container .main-container {
            max-width: 1600px;
            min-height: calc(100vh - 80px);
        }

        .narrow-container .main-container {
            max-width: 900px;
            min-height: auto;
        }

        .navbar .nav-link { 
            color: white !important; 
            transition: 0.3s; 
        }
        .navbar .nav-link:hover { 
            color: #cccccc !important; 
        }
        .navbar .nav-link.active-link { 
            color: black !important; 
            font-weight: bold; 
        }

        .dashboard-card { margin-bottom: 20px; }

        label.form-label {
            font-weight: 600;
            color: #1a1a1a;
        }

        .form-text-custom {
            font-size: 0.88rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 4px;
        }

        .modal-content { border-radius: 12px; }
        .modal-header { border-bottom: 2px solid #ffc107; }

        /*  DARK MODE STYLES */
        body.dark-mode {
            background-color: #121212;
            color: #f5f5f5;
        }

        body.dark-mode .navbar {
            background-color: #2e0854 !important; /* very dark violet */
        }

        body.dark-mode .main-container {
            background-color: #1e1e2f;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(255,255,255,0.2);
        }

        body.dark-mode .form-label {
            color: #ddd;
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #2a2a3b;
            color: #fff;
            border: 1px solid #444;
        }

        body.dark-mode .dropdown-menu {
            background-color: #2a2a3b;
            color: #fff;
        }

        body.dark-mode .dropdown-item {
            color: #fff;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #3b3b52;
        }
        /*  END DARK MODE */
    </style>
</head>
<body class="<?php 
    $uri = uri_string();
    echo in_array($uri, ['', 'home', 'about', 'contact']) ? 'narrow-container' : 'wide-container';
?>">

    <?= $this->include('templates/header') ?>

    <div class="main-container">
        <?= $this->renderSection('content') ?>
    </div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= base_url('auth/updateUserRole') ?>" method="post" id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUserId">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="editUserName" required>
                        <div class="form-text-custom">This name serves as your display name.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" disabled>
                        <div class="form-text-custom">Email cannot be changed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" id="editUserRole">
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  DARK MODE SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Apply saved mode
    if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('dark-mode');
        if (toggle) toggle.checked = true;
    }

    // Toggle mode
    if (toggle) {
        toggle.addEventListener('change', function () {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        });
    }

    // Existing modal code
    const editButtons = document.querySelectorAll('.btn-edit-user');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const role = this.dataset.role;

            document.getElementById('editUserId').value = id;
            document.getElementById('editUserName').value = name;
            document.getElementById('editUserEmail').value = email;
            document.getElementById('editUserRole').value = role;

            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        });
    });
});
</script>

</body>
</html>

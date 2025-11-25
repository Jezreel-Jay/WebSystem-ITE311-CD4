<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* ==================== BASE STYLES ==================== */
        body { 
            font-family: "Poppins", sans-serif; 
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
            background-color: #f5f7fa;
        }

        .main-container {
            border-radius: 12px;
            padding: 30px; 
            margin: 20px auto;        
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            background-color: #ffffff;
        }

        .wide-container .main-container {
            max-width: 1600px;
            min-height: calc(100vh - 80px);
        }

        .narrow-container .main-container {
            max-width: 900px;
        }

        /* ==================== NAVBAR ==================== */

        .welcome-box {
            border: 2px solid rgba(19, 67, 179, 0.5); 
            border-radius: 12px;                     
            background-color: rgba(255, 255, 255, 0.1); 
            transition: all 0.3s ease;
        }
        .welcome-box:hover {
            background-color: rgba(19, 67, 179, 0.1); 
            border-color: #1343b3;}

        .navbar {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
        }

        .navbar .nav-link { 
            color: #e0e0e0 !important; 
            transition: 0.3s; 
        }
        .navbar .nav-link:hover { 
            color: #ffd700 !important;
        }
        .navbar .nav-link.active-link { 
            color: #ffda6b !important;
            font-weight: bold; 
        }

        /* ==================== CARD & PANEL COLORS ==================== */
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        /* Role based card colors */
        .card-admin { background-color: #ffd700; color: #222 !important; }
        .card-teacher { background-color: #ffd700; color: #222 !important; }
        .card-student { background-color: #ffd700; color: #222 !important; }

        /* Badges */
        .badge-admin { background-color: #6a0dad; }
        .badge-teacher { background-color: #ff69b4; }
        .badge-student { background-color: #0b3d91; }

        /* Buttons */
        .btn-admin { background-color: #ffd700; color: #fff; border-radius: 8px; }
        .btn-admin:hover { background-color: #ffd700; }
        .btn-teacher { background-color: #ff69b4; color: #fff; border-radius: 8px; }
        .btn-teacher:hover { background-color: #ff85c1; }
        .btn-student { background-color: #0b3d91; color: #fff; border-radius: 8px; }
        .btn-student:hover { background-color: #1343b3; }
        .btn-add-user {background-color: #1343b3; color: #fff; border-radius: 8px; }
        .btn-add-user:hover { background-color: #0f2a80; }
        .btn-add-user-submit { background-color: #1343b3; color: #fff; border-radius: 8px; } 
        .btn-add-user-submit:hover { background-color: #0f2a80;  }

        /* Status badges */
        .status-active { background-color: #1e90ff; color: #fff; }
        .status-restricted { background-color: #6c757d; color: #fff; }
        .status-deleted { background-color: #dc3545; color: #fff; }

        /* ==================== DARK MODE ==================== */
        /* Dark mode */


        .dark-mode .userlist-wrapper .card-header {
            background-color: #1a3b5a !important; /* darker blue for dark mode */
        }





        body.dark-mode {
            background-color: #121212;
            color: #b0b0b0;
        }

        body.dark-mode .main-container {
            background-color: #1e1e2f;
            box-shadow: 0 4px 10px rgba(255,255,255,0.08);
        }

        body.dark-mode .navbar {
            background: linear-gradient(90deg, #2c0d55, #4b0082);
        }

        body.dark-mode .nav-link { color: #b0b0b0 !important; }
        body.dark-mode .nav-link:hover { color: #ffd700 !important; }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #2a2a3b;
            color: #fff;
            border: 1px solid #444;
        }

        body.dark-mode .badge { color: #fff !important; }

        
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

    <!-- ==================== EDIT USER MODAL ==================== -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header card-admin"> <!-- dynamic purple header -->
                    <h5 class="modal-title text-white" id="editUserModalLabel">
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reset Password</label>
                            <input type="password" name="password" class="form-control" id="editUserPassword">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="editUserConfirmPassword">
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
                        <button type="submit" class="btn btn-admin">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== DYNAMIC JS ==================== -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // DARK MODE TOGGLE
        const toggle = document.getElementById('darkModeToggle');
        const body = document.body;

        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
            if (toggle) toggle.checked = true;
        }

        if (toggle) {
            toggle.addEventListener('change', function () {
                body.classList.toggle('dark-mode');
                localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
            });
        }

        // EDIT MODAL POPULATION
        // CREATE MODAL INSTANCE ONCE (fixes glitch)
        const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        const modalHeader = document.querySelector('#editUserModal .modal-header');

        // EDIT MODAL POPULATION
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

                // Apply header color based on role
                modalHeader.classList.remove('card-admin', 'card-teacher', 'card-student');
                modalHeader.classList.add('card-' + role);

                // Show modal
                editUserModal.show();
            });
        });


    });
    </script>

</body>
</html>

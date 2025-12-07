<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // ========================= DASHBOARD =========================
    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        $role = $session->get('role');
        $userModel = new UserModel();

        $data = [
            'name' => $session->get('userName'),
            'email' => $session->get('userEmail'),
            'role' => $role,
            'currentUsers' => $userModel->where('is_deleted', 0)->countAllResults(),
            'admins' => $userModel->where(['role' => 'admin', 'is_deleted' => 0])->countAllResults(),
            'teachers' => $userModel->where(['role' => 'teacher', 'is_deleted' => 0])->countAllResults(),
            'students' => $userModel->where(['role' => 'student', 'is_deleted' => 0])->countAllResults(),
            'courses' => 0,
            'myCourses' => 0,
            'myStudents' => 0,
            'enrolledCourses' => 0,
            'completedLessons' => 0,
            'allUsers' => ($role === 'admin') ? $userModel->where('is_deleted', 0)->findAll() : [],
        ];

        return view('auth/dashboard', $data);
    }

    // ========================= ADD ROLE =========================
    public function addRole()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to(base_url('login'));
        }

        $roleName = trim((string) $this->request->getPost('new_role'));
        if ($roleName === '') {
            return redirect()->back()->with('error', 'Role name cannot be empty.');
        }

        $rolesFile = WRITEPATH . 'roles.json';
        $roles = file_exists($rolesFile) ? json_decode(file_get_contents($rolesFile), true) : ['admin', 'teacher', 'student'];

        if (!in_array(strtolower($roleName), array_map('strtolower', $roles))) {
            $roles[] = $roleName;
            file_put_contents($rolesFile, json_encode($roles));
            return redirect()->back()->with('success', 'New role added successfully!');
        } else {
            return redirect()->back()->with('error', 'Role already exists.');
        }
    }

    // ========================= LOGIN =========================
    public function login()
    {
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('login');
    }

    public function attempt()
    {
        $request = $this->request;
        $email = trim((string)$request->getPost('email'));
        $password = (string)$request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('login_error', 'Invalid credentials');
        }

        // Block deleted users
        if ($user['is_deleted'] == 1) {
            return redirect()->back()->with('login_error', 'Your account has been deleted.');
        }

        // Block restricted users
        if (isset($user['status']) && $user['status'] === 'restricted') {
            return redirect()->back()->with('login_error', 'Your account has been restricted.');
        }

        // Check password
        if (password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'isLoggedIn' => true,
                'userEmail' => $email,
                'userName' => $user['name'],
                'role' => $user['role'],
                'userId' => $user['id'],
                'userPasswordHash' => $user['password'],
            ]);

            return redirect()->to(base_url('dashboard'));
        }

        return redirect()->back()->with('login_error', 'Invalid credentials');
    }

    //login with 2FA
// public function attempt()
//     {
//         $request = $this->request;
//         $email = trim((string)$request->getPost('email'));
//         $password = (string)$request->getPost('password');

//         $userModel = new UserModel();
//         $user = $userModel->where('email', $email)->first();

//         if (!$user || !password_verify($password, $user['password'])) {
//             return redirect()->back()->with('login_error', 'Invalid credentials');
//         }

//         // Check for deleted or restricted status
//         if ($user['is_deleted'] == 1) {
//             return redirect()->back()->with('login_error', 'Your account has been deleted.');
//         }
//         if (isset($user['status']) && $user['status'] === 'restricted') {
//             return redirect()->back()->with('login_error', 'Your account has been restricted.');
//         }

//         $session = session();

//         // 2FA LOGIC START
//         if ($user['is_2fa_enabled'] == 1) {

//             // 1. Generate a 6-digit OTP and set expiry (5 minutes = 300 seconds)
//             $otpCode = (string)rand(100000, 999999);
//             $expiryTime = date('Y-m-d H:i:s', time() + 300); 

//             // 2. Store OTP and Expiry in DB
//             $userModel->update($user['id'], [
//                 'otp_code' => $otpCode,
//                 'otp_expires_at' => $expiryTime,
//             ]);

//             // 3. Send the OTP email
//             $emailSent = $this->sendOtpEmail($user['email'], $otpCode);
            
//             if (!$emailSent) {
//                 // If the debugger did not halt execution, redirect with an error.
//                 return redirect()->back()->with('login_error', 'Login successful, but failed to send 2FA code. Please check email configuration.');
//             }

//             // 4. Set temporary session state (only the user ID)
//             $session->set('2fa_user_id', $user['id']);
            
//             // 5. Redirect to the dedicated OTP verification page
//             return redirect()->to(base_url('verify-otp'));
//         }

//         // --- Standard Login (2FA Disabled) ---
//         $session->set([
//             'isLoggedIn' => true,
//             'userEmail' => $email,
//             'userName' => $user['name'],
//             'role' => $user['role'],
//             'userId' => $user['id'],
//             'userPasswordHash' => $user['password'],
//         ]);

//         return redirect()->to(base_url('dashboard'));
//     }
    
    // ========================= LOGOUT =========================
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    // ========================= REGISTER =========================
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('register');
    }

    public function store()
    {
        helper('html');

        $name = esc(trim((string)$this->request->getPost('name')));
        $email = esc(trim((string)$this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');
        $passwordConfirm = (string)$this->request->getPost('password_confirm');
        $role = (string)$this->request->getPost('role');

        // Validate required fields
        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            return redirect()->back()->withInput()->with('register_error', 'All fields are required.');
        }

        // Name validation
        if (!preg_match('/^[a-zA-Z0-9\s.-]+$/', $name)) {
            return redirect()->back()->withInput()->with('register_error', 'Name contains invalid symbols.');
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\'"<>]/', $email)) {
            return redirect()->back()->withInput()->with('register_error', 'Invalid email or forbidden characters.');
        }

        // Password confirmation
        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('register_error', 'Passwords do not match.');
        }

        // Prevent quotes in password
        if (preg_match('/[\'"]/', $password)) {
            return redirect()->back()->withInput()->with('register_error', 'Password cannot contain quotes.');
        }

        $userModel = new UserModel();

        // Only one admin from registration
        if ($role === 'admin' && $userModel->where('role', 'admin')->first()) {
            return redirect()->back()->withInput()->with('register_error', 'Admin registration is closed.');
        }

        // Prevent duplicate email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('register_error', 'Email already registered.');
        }

        // Save user
        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('login'))->with('register_success', 'Account created successfully.');
    }

    // ========================= ADD USER BY ADMIN =========================
    public function addUserByAdmin()
    {
        $session = session();
        if ($session->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('add_error', 'Access denied.');
        }

        helper('html');
        $name = esc(trim((string)$this->request->getPost('name')));
        $email = esc(trim((string)$this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');
        $passwordConfirm = (string)$this->request->getPost('password_confirm');
        $role = (string)$this->request->getPost('role');

        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '' || $role === '') {
            return redirect()->back()->withInput()->with('add_error', 'All fields are required.');
        }

        if (!preg_match('/^[a-zA-Z0-9\s.-]+$/', $name)) {
            return redirect()->back()->withInput()->with('add_error', 'Name contains invalid symbols.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\'"<>]/', $email)) {
            return redirect()->back()->withInput()->with('add_error', 'Invalid email or forbidden characters.');
        }

        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('add_error', 'Passwords do not match.');
        }

        if (preg_match('/[\'"]/', $password)) {
            return redirect()->back()->withInput()->with('add_error', 'Password cannot contain single or double quotes.');
        }

        $userModel = new UserModel();

        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('add_error', 'Email already exists.');
        }

        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('manage-users'))->with('add_success', "New {$role} account '{$name}' added successfully!");
    }


    // ========================= UPDATE USER ROLE (DEFAULT PASSWORD OPTIONAL) =========================
    public function updateUserRole()
    {
        $userModel = new UserModel();
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $role = $this->request->getPost('role');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $defaultPassword = $this->request->getPost('default_password');

        if (!$id) return redirect()->back()->with('error', 'Invalid user ID.');

        $user = $userModel->find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $updateData = ['name' => $name];

        // PROTECTED MASTER ADMIN ROLE
        if ($id != 1) {
            $updateData['role'] = $role;
        } else {
            $updateData['role'] = $user['role']; // cannot change master admin role
        }

        // PASSWORD CHANGE LOGIC (verify default password only if new password entered)
        if (!empty($newPassword)) {

            // Require default password only if changing password
            if (empty($defaultPassword) || !password_verify($defaultPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Default password is required and must be correct to change password.');
            }

            // Confirm new password
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'Passwords do not match.');
            }

            // Prevent quotes
            if (preg_match('/[\'"]/', $newPassword)) {
                return redirect()->back()->with('error', 'Password cannot contain quotes.');
            }

            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);

        // Auto logout if master admin changes own password
        if ($id == 1 && session()->get('userId') == 1 && !empty($newPassword)) {
            session()->destroy();
            return redirect()->to(base_url('login'))
                            ->with('success', 'Your account has been updated. Please log in again.');
        }

        return redirect()->back()->with('success', "User '{$name}' updated successfully.");
    }



        // ========================= DELETE USER =========================
        public function deleteUser()
        {
            $id = $this->request->getPost('id');
            $userModel = new UserModel();

            $user = $userModel->find($id);

            //  PROTECT ADMIN USERS
            if ($user['role'] === 'admin') {
                return redirect()->back()->with('error', 'Admin accounts cannot be deleted.');
            }

            if ($id == 1) return redirect()->back()->with('error', 'Cannot delete master admin.');

            $userModel->update($id, ['is_deleted' => 1, 'status' => 'deleted']);

            return redirect()->back()->with('success', 'User deleted successfully.');
        }

    // ========================= RESTRICT USER =========================
    public function restrictUser()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        $id = $this->request->getPost('id');
        $userModel = new UserModel();

        $user = $userModel->find($id);

        //  PROTECT ADMIN
        if ($user['role'] === 'admin') {
            return redirect()->back()->with('error', 'Admin cannot be restricted.');
        }

        if ($id == 1) {
            return redirect()->back()->with('error', 'Main admin cannot be restricted.');
        }

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $userModel->update($id, ['status' => 'restricted']);

        $userName = $user['name'];

        return redirect()->to(base_url('manage-users'))
            ->with('success', "User '{$userName}' has been restricted.");
    }

    // ========================= VIEW RESTRICTED USERS =========================
    public function restrictedUsers()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('login');
        }

        $userModel = new UserModel();
        $data = [
            'name' => $session->get('userName'),
            'role' => $session->get('role'),
            'restrictedUsers' => $userModel->where('status', 'restricted')->where('is_deleted', 0)->findAll(),
        ];

        return view('auth/restricted_users', $data);
    }

    // ========================= UNRESTRICT USER =========================
    public function unrestrictUser()
    {
        $id = $this->request->getPost('id');
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $userModel->update($id, ['status' => null]);
        return redirect()->back()->with('success', "User '{$user['name']}' unrestricted successfully.");
    }

    // ========================= DELETE USER PERMANENTLY =========================
    public function deleteUserPermanent()
    {
        $id = $this->request->getPost('id');
        $userModel = new UserModel();

        $user = $userModel->find($id);

        //  PROTECT ADMIN
        if ($user['role'] === 'admin') {
            return redirect()->back()->with('error', 'Admin accounts cannot be permanently deleted.');
        }

        if ($id == 1) {
            return redirect()->back()->with('error', 'Cannot delete master admin.');
        }

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Just mark deleted
        $userModel->update($id, ['is_deleted' => 1]);

        return redirect()->back()->with('success', 'User permanently deleted.');
    }

    // ========================= MANAGE USERS =========================
    public function manageUsers()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('login');
        }

        $userModel = new UserModel();
        $data = [
            'name' => $session->get('userName'),
            'role' => $session->get('role'),
            'allUsers' => $userModel->where('is_deleted', 0)->findAll(),
            'masterAdminId' => 1
        ];

        return view('auth/manage_users', $data);
    }

    public function manage_users()
    {
        $userModel = new UserModel();
        $viewType = $this->request->getGet('view');

        if ($viewType === 'restricted') {
            $data['users'] = $userModel->where('status', 'restricted')->findAll();
            $data['viewType'] = 'restricted';
        } else {
            $data['users'] = $userModel->where('status', 'active')->findAll();
            $data['viewType'] = 'active';
        }

        $data['role'] = session()->get('role');
        return view('auth/manage_users', $data);
    }

    // ========================= EDIT USER PAGE =========================
    public function editUser($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to(base_url('login'));
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) return redirect()->to(base_url('manage-users'))->with('error', 'User not found.');

        return view('auth/edit_user', [
            'user' => $user,
            'role' => $session->get('role'),
        ]);
    }

    public function settings()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        return view('auth/settings');
    }


    public function resetPassword()
        {
            $id = $this->request->getPost('id');
            $defaultPassword = trim($this->request->getPost('default_password'));
            $newPassword = trim($this->request->getPost('new_password'));
            $confirmPassword = trim($this->request->getPost('confirm_password'));

            if (!$id || !$defaultPassword) {
                return redirect()->back()->with('error', 'Default password is required.');
            }
            $userModel = new UserModel();
            $user = $userModel->find($id);
            if (!$user) return redirect()->back()->with('error', 'User not found.');

             //  Verify default password ===
            if (!password_verify($defaultPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Default password is incorrect.');
            }
            // Decide which password to save
            if (!empty($newPassword)) {
                // Validate new password against confirm
                if ($newPassword !== $confirmPassword) {
                    return redirect()->back()->with('error', 'Passwords do not match.');
                }
                $passwordToSave = $newPassword; // Use new password if provided
            } else {
                $passwordToSave = $defaultPassword; // Otherwise use default
            }

            // Prevent quotes in whichever password is used
            if (preg_match('/[\'"]/', $passwordToSave)) {
                return redirect()->back()->with('error', 'Password cannot contain quotes.');
            }

            $userModel->update($id, [
                'password' => password_hash($passwordToSave, PASSWORD_DEFAULT),
                'status' => 'active'
            ]);

            // Auto logout if master admin changes own password
            if ($id == 1 && session()->get('userId') == 1) {
                session()->destroy();
                return redirect()->to(base_url('login'))
                                ->with('success', 'Your password has been updated. Please log in again.');
            }

            return redirect()->back()->with('success', "Password reset successfully for {$user['name']}.");
        }

        // ========================= GENERATE DEFAULT PASSWORD =========================
        // Purpose: Generates a new password, updates user default password, returns password to modal
        public function generateDefaultPassword($id)
        {
            $userModel = new UserModel();
            $user = $userModel->find($id);

            if (!$user) {
                return $this->response->setJSON(['error' => 'User not found']);
            }

            // Generate random password, 8 chars
            $newPassword = bin2hex(random_bytes(4)); // 8 char random hex

            // Update user's password in DB
            $userModel->update($id, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'status' => 'active' // ensure account active
            ]);

            // Return new password for modal field
            return $this->response->setJSON(['password' => $newPassword]);
        }

        // ========================= SEND OTP EMAIL =========================
    /**
     * Sends the one-time password to the user's email.
     */


        private function sendOtpEmail(string $recipient, string $otpCode): bool
        {
            $email = \Config\Services::email();
            
            // Explicitly set mail type to HTML, matching your config
            $email->setMailType('html'); 

            $email->setTo($recipient);
            $email->setSubject('Your Two-Factor Authentication Code');
            
            // Use HTML tags for proper formatting
            $message = "
            <p>Hello,</p>
            <p>Your one-time verification code is: <strong>{$otpCode}</strong></p>
            <p>This code will expire in 5 minutes.</p>
            <p>Please enter this code on the login screen to access your account.</p>";
            
            $email->setMessage($message);
            
            // ... (rest of the function)
            if (!$email->send(false)) { 
                // ...
                return false;
            }
            return true;
        }


    // ========================= VERIFY OTP =========================
// ========================= VERIFY OTP (CORRECTED) =========================
public function verifyOtp()
{
    $session = session();
    $userId = $session->get('2fa_user_id');

    // Security Check: Must have a pending 2FA login
    if (!$userId) {
        // FIX 1: Add return here for security redirect
        return redirect()->to(base_url('login'))->with('login_error', 'Access denied. Please log in first.');
    }
    
    $userModel = new UserModel();
    $user = $userModel->find($userId);

    // If it's a GET request, show the form
    if ($this->request->getMethod() !== 'post') {
        return view('auth/verify_otp', ['user' => $user]); 
    }

    // If it's a POST request, handle validation
    $otpEntered = trim((string)$this->request->getPost('otp_code'));
    
    if (!$user) {
        return redirect()->to(base_url('login'))->with('login_error', 'User not found or session expired.');
    }
    
    $isOtpValid = ($otpEntered === $user['otp_code']);
    $isNotExpired = (strtotime($user['otp_expires_at']) > time());
    
    if ($isOtpValid && $isNotExpired) {
        
        // SUCCESS: Finalize login
        
        // Clear the OTP and temporary session state
        $userModel->update($userId, ['otp_code' => null, 'otp_expires_at' => null]);
        $session->remove('2fa_user_id');

        // Set the permanent 'isLoggedIn' session data
        $session->set([
            'isLoggedIn' => true,
            'userEmail' => $user['email'],
            'userName' => $user['name'],
            'role' => $user['role'],
            'userId' => $user['id'],
            'userPasswordHash' => $user['password'],
        ]);

        // FIX 2: Add return here to redirect to dashboard on success
        return redirect()->to(base_url('dashboard'));

    } else {
        // FAILURE: Invalid or expired OTP
        return redirect()->back()->with('error', 'Invalid or expired verification code. Please try logging in again.');
    }

}
}
<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // ========================= DASHBOARD =========================
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        $role = $session->get('role');
        $userModel = new UserModel();

        // Prepare data for dashboard
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

    // public function attempt()
    // {
    //     $request = $this->request;
    //     $email = trim((string)$request->getPost('email'));
    //     $password = (string)$request->getPost('password');

    //     $userModel = new UserModel();
    //     $user = $userModel->where('email', $email)->first();

    //     if ($user && password_verify($password, $user['password'])) {
    //         $session = session();
    //         $session->set([
    //             'isLoggedIn' => true,
    //             'userEmail' => $email,
    //             'userName' => $user['name'],
    //             'role' => $user['role'],
    //         ]);

    //         return redirect()->to(base_url('dashboard'));
    //     }

    //     return redirect()->back()->with('login_error', 'Invalid credentials');
    // }

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
            ]);

            

            return redirect()->to(base_url('dashboard'));
        }

        return redirect()->back()->with('login_error', 'Invalid credentials');
    }


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

    // // ========================= ADD USER BY ADMIN =========================
    // public function addUserByAdmin()
    // {
    //     $session = session();
    //     if ($session->get('role') !== 'admin') {
    //         return redirect()->to(base_url('dashboard'))->with('add_error', 'Access denied.');
    //     }

    //     helper('html');
    //     $name = esc(trim((string)$this->request->getPost('name')));
    //     $email = esc(trim((string)$this->request->getPost('email')));
    //     $password = (string)$this->request->getPost('password');
    //     $passwordConfirm = (string)$this->request->getPost('password_confirm');
    //     $role = (string)$this->request->getPost('role');

    //     if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '' || $role === '') {
    //         return redirect()->back()->withInput()->with('add_error', 'All fields are required.');
    //     }

    //     $userModel = new UserModel();

    //     if ($password !== $passwordConfirm) {
    //         return redirect()->back()->withInput()->with('add_error', 'Passwords do not match.');
    //     }

    //     if ($userModel->where('email', $email)->first()) {
    //         return redirect()->back()->withInput()->with('add_error', 'Email already exists.');
    //     }

    //     $userModel->insert([
    //         'name' => $name,
    //         'email' => $email,
    //         'role' => $role,
    //         'password' => password_hash($password, PASSWORD_DEFAULT),
    //     ]);

    //     return redirect()->to(base_url('manage-users'))->with('add_success', "New {$role} account '{$name}' added successfully!");
    // }

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

        //  Required fields
        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '' || $role === '') {
            return redirect()->back()->withInput()->with('add_error', 'All fields are required.');
        }

        //  Name validation (no symbols)
        if (!preg_match('/^[a-zA-Z0-9\s.-]+$/', $name)) {
            return redirect()->back()->withInput()->with('add_error', 'Name contains invalid symbols.');
        }

        //  Email validation (valid format + no quotes or < >)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\'"<>]/', $email)) {
            return redirect()->back()->withInput()->with('add_error', 'Invalid email or forbidden characters.');
        }

        //  Password match
        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('add_error', 'Passwords do not match.');
        }

        //  Password validation (no single or double quotes)
        if (preg_match('/[\'"]/', $password)) {
            return redirect()->back()->withInput()->with('add_error', 'Password cannot contain single or double quotes.');
        }

        $userModel = new UserModel();

        //  Prevent duplicate email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('add_error', 'Email already exists.');
        }

        //  Save user
        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('manage-users'))->with('add_success', "New {$role} account '{$name}' added successfully!");
    }


    // ========================= UPDATE USER ROLE =========================
    public function updateUserRole()
    {
        $userModel = new UserModel();
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $role = $this->request->getPost('role');

        if (!$id) return redirect()->back()->with('error', 'Invalid user ID.');

        $userModel->update($id, ['name' => $name, 'role' => $role]);

        return redirect()->back()->with('success', "User '{$name}' updated successfully.");
    }

    // ========================= DELETE USER =========================
    public function deleteUser()
    {
        $id = $this->request->getPost('id');
        $userModel = new UserModel();

        if ($id == 1) return redirect()->back()->with('error', 'Cannot delete master admin.');

        $userModel->update($id, ['is_deleted' => 1, 'status' => 'deleted']);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // ========================= RESTRICT USER =========================
    // public function restrictUser()
    // {
    //     $session = session();
    //     if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
    //         return redirect()->to(base_url('login'))->with('error', 'Access denied.');
    //     }

    //     $id = $this->request->getPost('id');
    //     $userModel = new UserModel();

    //     if ($id == 1) return redirect()->back()->with('error', 'Main admin cannot be restricted.');

    //     $user = $userModel->find($id);
    //     if (!$user) return redirect()->back()->with('error', 'User not found.');

    //     $userModel->update($id, ['status' => 'restricted']);

    //     return redirect()->to(base_url('manage-users'))->with('success', "User '{$user['name']}' has been restricted.");
    // }

        public function restrictUser()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to(base_url('login'))->with('error', 'Access denied.');
        }

        $id = $this->request->getPost('id');
        $userModel = new UserModel();

        if ($id == 1) {
            return redirect()->back()->with('error', 'Main admin cannot be restricted.');
        }

        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $userModel->update($id, ['status' => 'restricted']);

        //  Handles both array or object
        $userName = is_array($user) ? $user['name'] : $user->name;

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


    $userName = is_array($user) ? $user['name'] : $user->name;
    
    $userModel->update($id, ['status' => null]);
    return redirect()->back()->with('success', "User '{$user['name']}' unrestricted successfully.");
}

// ========================= DELETE USER PERMANENTLY =========================
public function deleteUserPermanent()
{
    $id = $this->request->getPost('id');
    $userModel = new UserModel();
    
    if ($id == 1) return redirect()->back()->with('error', 'Cannot delete master admin.');


    $userModel->delete($id, true);
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

        // Path matches file location
        return view('auth/settings');
    }


    
}

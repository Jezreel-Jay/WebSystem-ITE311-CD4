<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
   
{
        public function dashboard()
    {
        $session = session();
        
    

        // Authorization check
        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        $role = $session->get('role');

        $userModel = new UserModel();

        
        $data = [
            'name' => $session->get('userName'),
            'email' => $session->get('userEmail'),
            'role'  => $session->get('role'),
            'currentUsers' =>  $userModel->countAllResults() ,
            'admins' => $userModel->where('role', 'admin')->countAllResults(),
            'teachers' =>  $userModel->where('role', 'teacher')->countAllResults(),
            'students' => $userModel->where('role', 'student')->countAllResults(),
            'courses' => 0,
            'myCourses' => 0,
            'myStudents' => 0,
            'enrolledCourses' => 0,
            'completedLessons' => 0,
            'allUsers' => ($role === 'admin') ? $userModel->findAll() : []
            
        ];

        return view('auth/dashboard', $data);
    }

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

        // For simulation — normally you'd save to DB
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



    /**
     * Show Login Page
     */
    public function login()
    {
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('login');
    }

    /**
     * Handle Login Attempt
     */
    public function attempt()
    {
        $request = $this->request;
        $email = trim((string) $request->getPost('email'));
        $password = (string) $request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'isLoggedIn' => true,
                'userEmail'  => $email,
                'userName'   => $user['name'],
                'role'       => $user['role'],                
             
                
            ]);
            return redirect()->to(base_url('dashboard'));
        }

        return redirect()->back()->with('login_error', 'Invalid credentials');
    }


    /**
     * Logout
     */
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('login'));
    }

    /**
     * Show Register Page
     */
    public function register()
    {
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }


        return view('register');
    }

    /**
     * Handle Registration
     */
    public function store()

    {
        helper('html');

        $name            = trim((string) $this->request->getPost('name'));
        $email           = trim((string) $this->request->getPost('email'));
        $password        = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');
        $role            = (string) $this->request->getPost('role');

        $name  = esc($name);
        $email = esc($email);
        

        // Validate required fields
        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            return redirect()->back()->withInput()->with('register_error', 'All fields are required.');
        }

        // Validate invalid symbols in name (allow only letters, spaces, and basic punctuation)
        if (!preg_match('/^[a-zA-Z0-9\s.-]+$/', $name)) {
            return redirect()->back()->withInput()->with('register_error', 'Name contains invalid symbols.');
        }

        // Validate invalid symbols in email (must be valid format and no extra symbols)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\'"<>]/', $email)) {
            return redirect()->back()->withInput()->with('register_error', 'Invalid email address or contains forbidden characters.');
        }

        // Validate password confirmation
        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('register_error', 'Passwords do not match.');
        }

        // Reject password with single or double quotes
        if (preg_match('/[\'"]/', $password)) {
            return redirect()->back()->withInput()->with('register_error', 'Password cannot contain single or double quotation marks.');
        }

        $userModel = new UserModel();

        // Allow only one admin registration from outside
        if ($role === 'admin') {
            $existingAdmin = $userModel->where('role', 'admin')->first();
            if ($existingAdmin) {
                return redirect()->back()->withInput()
                    ->with('register_error', 'Admin registration is closed. Please contact the system administrator.');
            }
        }


        // Check for existing email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('register_error', 'Email is already registered.');
        }

        // Save user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userId = $userModel->insert([
            'name'     => $name,
            'email'    => $email,
            'role'     => $role,
            'password' => $passwordHash,
        ], true);

        if (!$userId) {
            return redirect()->back()->withInput()->with('register_error', 'Registration failed.');
        }

        return redirect()
            ->to(base_url('login'))
            ->with('register_success', 'Account created successfully. Please log in.');
    }
    

    //ADDuserbyAdmin
    public function addUserByAdmin()
    {
        $session = session();
        if ($session->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('add_error', 'Access denied.');
        }

        helper('html');
        $name = trim((string) $this->request->getPost('name'));
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');
        $role = (string) $this->request->getPost('role');

        $name  = esc($name);
        $email = esc($email);

        // === VALIDATION (same as your register form) ===
        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '' || $role === '') {
            return redirect()->back()->withInput()->with('add_error', 'All fields are required.');
        }

        if (!preg_match('/^[a-zA-Z0-9\s.-]+$/', $name)) {
            return redirect()->back()->withInput()->with('add_error', 'Name contains invalid symbols.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\'"<>]/', $email)) {
            return redirect()->back()->withInput()->with('add_error', 'Invalid email address or contains forbidden characters.');
        }

        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('add_error', 'Passwords do not match.');
        }

        if (preg_match('/[\'"]/', $password)) {
            return redirect()->back()->withInput()->with('add_error', 'Password cannot contain single or double quotes.');
        }

        $userModel = new UserModel();

        // Check for duplicate email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('add_error', 'Email already exists.');
        }

        // Insert user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $userModel->insert([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
        ]);

        if ($userId) {
            return redirect()
                ->to(base_url('dashboard'))
                ->with('add_success', "New {$role} account '{$name}' added successfully!");
        }

        return redirect()->back()->withInput()->with('add_error', 'Failed to add user.');
    }

    public function updateUserRole()
{
    $id = $this->request->getPost('id');
    $role = $this->request->getPost('role');
    $userModel = new UserModel();

    if ($id == 1) {
        return redirect()->back()->with('error', 'Main admin cannot be modified.');
    }

    if (!in_array($role, ['admin', 'teacher', 'student'])) {
        return redirect()->back()->with('error', 'Invalid role.');
    }

    $userModel->update($id, ['role' => $role]);
    return redirect()->back()->with('success', 'User role updated successfully!');
}

public function deleteUser()
{
    $id = $this->request->getPost('id');
    $userModel = new UserModel();

    // Prevent deleting main admin
    if ($id == 1) {
        return redirect()->back()->with('error', 'Main admin cannot be deleted.');
    }

    // Get user info before deleting
    $user = $userModel->find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Delete user
    $userModel->delete($id);

    // Get name or email for message
    $userName = $user['name'] ?? $user['email'] ?? 'Unknown User';

    return redirect()->back()->with('success', "User '{$userName}' deleted successfully!");
}



}

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
        $data = [
            'name' => $session->get('userName'),
            'email' => $session->get('userEmail'),
            'role'  => $session->get('role'),
            
        ];

        return view('auth/dashboard', $data);
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
        $name            = trim((string) $this->request->getPost('name'));
        $email           = trim((string) $this->request->getPost('email'));
        $password        = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');
        $role            = (string) $this->request->getPost('role');


        // Validate required fields
        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            return redirect()->back()->withInput()->with('register_error', 'All fields are required.');
        }

        // Validate email format
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('register_error', 'Invalid email address.');
        }

        // Validate password confirmation
        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('register_error', 'Passwords do not match.');
        }

        $userModel = new UserModel();

        // Check for existing email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('register_error', 'Email is already registered.');
        }

        // Save user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userId = $userModel->insert([
            'name'     => $name,
            'email'    => $email,
            'role'     => $role, //modified role
            'password' => $passwordHash,
        ], true);

        if (! $userId) {
            return redirect()->back()->withInput()->with('register_error', 'Registration failed.');
        }

        // Redirect to login with success
        return redirect()
            ->to(base_url('login'))
            ->with('register_success', 'Account created successfully. Please log in.');
    }
}

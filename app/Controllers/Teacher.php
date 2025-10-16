<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Teacher extends Controller
{
    public function dashboard()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return redirect()->to('/login');
        }

        return view('teacher_dashboard');
    }
}

<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');
        $uri = service('uri')->getPath(); // example 'admin/dashboard'

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Admin can access anything starting with /admin
        if (str_starts_with($uri, 'admin') && $role !== 'admin') {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }

        // Teacher can access anything starting with /teacher
        if (str_starts_with($uri, 'teacher') && $role !== 'teacher') {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }

        // Student can only access student or announcements
        if ((str_starts_with($uri, 'student') || $uri === 'announcements') && $role !== 'student') {
            // allowed for student, so do nothing
        } elseif ($role === 'student' && !str_starts_with($uri, 'student') && $uri !== 'announcements') {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

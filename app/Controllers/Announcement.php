<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Announcement extends Controller
{
    public function index()
    {
        // Load model
        $announcementModel = new AnnouncementModel();

        // Order by created_at DESC (newest first)
        $data['announcements'] = $announcementModel
        ->orderBy('created_at', 'DESC')
        ->findAll();

        // Load view
        return view('announcements', $data);
    }
}

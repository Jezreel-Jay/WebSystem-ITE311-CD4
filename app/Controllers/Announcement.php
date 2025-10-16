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

        // Fetch all announcements (will be empty if table not yet created)
        $data['announcements'] = $announcementModel->findAll();

        // Load view
        return view('announcements', $data);
    }
}

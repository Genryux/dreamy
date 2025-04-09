<?php

namespace App\Http\Controllers;

use App\Events\RecentApplicationTableUpdated;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function getRecentApplications() {

        $applications = ApplicationForm::latest()->limit(10)->get();
    
        return view('user-admin.dashboard', ['applications' => $applications]);

    }

    public function getEnrollmentStat() {

    }
}

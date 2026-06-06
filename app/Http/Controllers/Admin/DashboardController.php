<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_clients'   => User::where('role', 'client')->where('active', true)->count(),
            'total_clients'    => User::where('role', 'client')->count(),
            'active_protocols' => 0,
            'readings_today'   => 0,
            'pending_surveys'  => 0,
        ];

        $recentClients = User::where('role', 'client')->latest()->take(5)->get();
        $recentActivity = [];

        return view('dashboard.admin', compact('stats', 'recentClients', 'recentActivity'));
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'centers'  => Center::count(),
            'admins'   => User::where('role', 'admin')->count(),
            'clients'  => User::where('role', 'client')->count(),
            'products' => Product::count(),
        ];

        $centers = Center::withCount(['admins', 'clients'])
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'centers'));
    }
}
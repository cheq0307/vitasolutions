<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $latestReadings = collect();
        $activeProtocols = collect();
        $pendingSurvey = null;

        return view('dashboard.cliente', compact('latestReadings', 'activeProtocols', 'pendingSurvey'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function logs(Request $request)
    {
        $path = storage_path('logs/security.log');

        if (File::exists($path)) {
            $logs = file($path);
            $logs = array_reverse($logs);
        } else {
            $logs = ["No security logs found."];
        }

        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');

        return view('admin.logs', compact('logs', 'dateFrom', 'dateTo'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_devices' => DeviceToken::count(),
            'active_devices' => DeviceToken::whereNotNull('last_used_at')
                ->where('last_used_at', '>=', now()->subDays(7))
                ->count(),
            'total_notifications' => Notification::count(),
            'recent_notifications' => Notification::recent()->count(),
        ];

        $recentNotifications = Notification::with('sentBy:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentNotifications'));
    }
}

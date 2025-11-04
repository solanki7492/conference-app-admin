<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->middleware('auth');
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display the notification form
     */
    public function create()
    {
        $deviceCount = DeviceToken::count();
        $devices = DeviceToken::with('user:id,name,email')->get();
        
        return view('admin.notifications.create', compact('deviceCount', 'devices'));
    }

    /**
     * Send notification
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'target_type' => 'required|in:all,specific',
            'target_tokens' => 'required_if:target_type,specific|array',
            'target_tokens.*' => 'string',
        ]);

        try {
            if ($request->target_type === 'all') {
                $result = $this->firebaseService->sendToAll(
                    $request->title,
                    $request->message,
                    Auth::id()
                );
            } else {
                $result = $this->firebaseService->sendToSpecificTokens(
                    $request->target_tokens,
                    $request->title,
                    $request->message,
                    Auth::id()
                );
            }

            if ($result['success']) {
                return redirect()->route('admin.notifications.index')
                    ->with('success', $result['message']);
            } else {
                return back()->withInput()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }

    /**
     * Display notification history
     */
    public function index()
    {
        $notifications = Notification::with('sentBy:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show notification details
     */
    public function show(Notification $notification)
    {
        $notification->load('sentBy:id,name');
        
        return view('admin.notifications.show', compact('notification'));
    }
}

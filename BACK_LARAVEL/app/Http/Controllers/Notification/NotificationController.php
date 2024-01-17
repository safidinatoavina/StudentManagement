<?php

namespace App\Http\Controllers\Notification;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Notification\NotificationService;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $notification=new NotificationService;
        return[
            'notification'=>$notification->get(),
            'badge_notification'=>$notification->badge()
        ];
    }

    public function markAsRead(Notification $notification)
    {
        $notification_service=new NotificationService;
        return $notification_service->read($notification);

    }

    public function markAsReadAll(Request $request)
    {
        $notification=new NotificationService;
        return $notification->readAll();

    }

    public function delete(Notification $notification)
    {
        $notification_service=new NotificationService;
        return $notification_service->delete($notification);

    }

}

<?php

namespace App\Services;

use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationService
{

    public function NotifyPrivateUser($user, $header, $message, $url = null, $sharedId = null)
    {
        return DB::transaction(function () use ($user, $header, $message, $url, $sharedId) {
            $user->notify(new PrivateQueuedNotification(
                $header,
                $message,
                $url,
                $sharedId
            ));

            Notification::route('broadcast', 'user.' . $user->id)
                ->notify(new PrivateImmediateNotification(
                    $header,
                    $message,
                    $url,
                    $sharedId,
                    'user.' . $user->id
                ));
        });
    }
}

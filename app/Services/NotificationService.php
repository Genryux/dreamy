<?php

namespace App\Services;

use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use Illuminate\Support\Facades\DB;

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

            $user->notify(new PrivateImmediateNotification(
                $header,
                $message,
                $url,
                $sharedId
            ));
        });
    }
}

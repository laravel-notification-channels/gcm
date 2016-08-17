<?php

namespace Fruitcake\NotificationChannels\Gcm;

use Fruitcake\NotificationChannels\Gcm\Exceptions\CouldNotSendNotification;
use Fruitcake\NotificationChannels\Gcm\Events\MessageWasSent;
use Fruitcake\NotificationChannels\Gcm\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class GcmChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws Fruitcake\NotificationChannels\Gcm\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}

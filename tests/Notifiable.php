<?php

namespace NotificationChannels\Gcm\Test;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return int
     */
    public function getKey()
    {
        return 1;
    }
}

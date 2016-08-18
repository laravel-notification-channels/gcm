<?php

namespace Fruitcake\NotificationChannels\Gcm\Exceptions;

class SendingFailed extends \Exception
{
    /**
     * @param \Exception $e
     * @return SendingFailed
     */
    public static function create(\Exception $e)
    {
        return new static("Cannot send message to Gcm: " . $e->getMessage(), 0, $e);
    }
}
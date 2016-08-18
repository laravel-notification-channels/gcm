<?php

namespace Fruitcake\NotificationChannels\Gcm\Exceptions;

class SendingFailed extends \Exception
{
    /**
     * @var \Exception
     */
    public $original;

    /**
     * @param \Exception $original
     * @return SendingFailed
     */
    public static function create($original)
    {
        $exception = new static("Cannot send message to Gcm: ". $original->getMessage());
        $exception->original = $original;
        return $exception;
    }
}
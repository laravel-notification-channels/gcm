<?php

namespace NotificationChannels\Gcm;

use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gcm\Exceptions\SendingFailed;
use ZendService\Google\Gcm\Client;

class GcmChannel
{
    /** @var Client */
    protected $client;

    /** @var Dispatcher */
    protected $events;

    /**
     * @param Client $client
     * @param Dispatcher $events
     */
    public function __construct(Client $client, Dispatcher $events)
    {
        $this->client = $client;
        $this->events = $events;
    }

    /**
     * Send the notification to Google Cloud Messaging.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     *
     * @throws Exceptions\SendingFailed
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('gcm');
        if (! $tokens) {
            return;
        }

        $message = $notification->toGcm($notifiable);
        if (! $message) {
            return;
        }

        $packet = $this->getPacket($tokens, $message);

        try {
            $response = $this->client->send($packet);
        } catch (Exception $exception) {
            throw SendingFailed::create($exception);
        }

        if (! $response->getFailureCount() == 0) {
            $this->handleFailedNotifications($notifiable, $notification, $response);
        }
    }

    /**
     * @param $tokens
     * @param $message
     *
     * @return \NotificationChannels\Gcm\Packet
     */
    protected function getPacket($tokens, $message)
    {
        $packet = new Packet();

        $packet->setRegistrationIds($tokens);
        $packet->setCollapseKey(str_slug($message->title));
        $packet->setData([
                'title' => $message->title,
                'message' => $message->message,
            ] + $message->data);
        $packet->setNotification([
                'title' => $message->title,
                'body' => $message->message,
                'sound' => $message->sound,
            ] + $message->data);

        return $packet;
    }

    /**
     * @param $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param $response
     */
    protected function handleFailedNotifications($notifiable, Notification $notification, $response)
    {
        $results = $response->getResults();

        foreach ($results as $token => $result) {
            if (! isset($result['error'])) {
                continue;
            }

            $this->events->fire(
                new NotificationFailed($notifiable, $notification, get_class($this), [
                    'token' => $token,
                    'error' => $result['error'],
                ])
            );
        }
    }
}

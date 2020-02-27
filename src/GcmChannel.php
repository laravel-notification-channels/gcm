<?php

namespace NotificationChannels\Gcm;

use Exception;
use Illuminate\Events\Dispatcher;
use ZendService\Google\Gcm\Client;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gcm\Exceptions\SendingFailed;
use Illuminate\Notifications\Events\NotificationFailed;

class GcmChannel
{
    /**
     * The GCM client instance.
     *
     * @var \ZendService\Google\Gcm\Client
     */
    protected $client;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new channel instance.
     *
     * @param \ZendService\Google\Gcm\Client $client
     * @param \Illuminate\Events\Dispatcher $events
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
     * @throws Exceptions\SendingFailed
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('gcm', $notification);
        if (empty($tokens)) {
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
                'icon' => $message->icon,
            ] + $message->notification);

        return $packet;
    }

    /**
     * Handle a failed notification.
     *
     * @param mixed $notifiable
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

            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, get_class($this), [
                    'token' => $token,
                    'error' => $result['error'],
                ])
            );
        }
    }
}

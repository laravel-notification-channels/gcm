<?php

namespace NotificationChannels\Gcm;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use ZendService\Google\Gcm\Client;
use ZendService\Google\Gcm\Message as Packet;

class GcmChannel
{
    /** @var Client */
    protected $client;

    /** @var  Dispatcher */
    protected $events;

    /**
     * GcmChannel constructor.
     *
     * @param Client $client
     * @param Dispatcher $events
     */
    public function __construct(Client $client, Dispatcher $events)
    {
        $this->client = $client;
        $this->events = $events;
    }

    /**
     * Send the notification to Google Cloud Messaging
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
        if (!$tokens) {
            return;
        }

        $message = $notification->toGcm($notifiable);
        if (!$message) {
            return;
        }

        // Create GCM Packet
        $packet = new Packet();
        $packet->setRegistrationIds($tokens);
        $packet->setCollapseKey(str_slug($message->title));
        $packet->setData([
                'title' => $message->title,
                'message' => $message->message
            ] + $message->data);

        try {
            $response = $this->client->send($packet);
        } catch(\Exception $e) {
            throw Exceptions\SendingFailed::create($e);
        }

        // Return when no errors occurred
        if($response->getFailureCount() == 0) {
            return;
        }

        // Fire event for each failed notification
        $results = $response->getResults();

        foreach($results as $token => $result) {
            if(!isset($result['error'])) {
                continue;
            }

            $this->events->fire(
                new NotificationFailed($notifiable, $notification, $this, [
                    'token' => $token,
                    'error' => $result['error']
                ])
            );
        }
    }
}

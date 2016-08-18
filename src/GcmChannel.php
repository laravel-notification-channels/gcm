<?php

namespace Fruitcake\NotificationChannels\Gcm;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Zend\Http\Client\Adapter\Curl;
use ZendService\Google\Gcm\Client;
use ZendService\Google\Gcm\Message as Packet;

class GcmChannel
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $client->setApiKey(config('services.gcm.key'));
        $client->getHttpClient()->setAdapter(new Curl());
        $this->client = $client;
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

            app()->make('events')->fire(
                new NotificationFailed($notifiable, $notification, $this, [
                    'token' => $token,
                    'error' => $result['error']
                ])
            );
        }
    }
}

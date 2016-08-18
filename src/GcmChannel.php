<?php

namespace Fruitcake\NotificationChannels\Gcm;

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
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = $notifiable->routeNotificationFor('gcm');
        if (!$tokens || count($tokens) == 0) {
            return;
        }
        if (!is_array($tokens)) {
            $tokens = [$tokens];
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
            // TODO; Should we fire NotificationFailed event here, or throw exception?
            app('log')->error('Error sending GCM notification to '. $notifiable->name .' (#'. $notifiable->id .') '. $e->getMessage());
            return;
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
                new Events\NotificationFailed($notifiable, $notification, $this, [
                    'token' => $token,
                    'error' => $result['error']
                ])
            );
        }
    }
}

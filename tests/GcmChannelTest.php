<?php

namespace NotificationChannels\Gcm\Tests;

use Mockery;
use Illuminate\Events\Dispatcher;
use ZendService\Google\Gcm\Client;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Gcm\GcmChannel;
use NotificationChannels\Gcm\GcmMessage;
use Illuminate\Notifications\Notification;

class ChannelTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Dispatcher
     */
    protected $events;

    /** @var Notification */
    protected $notification;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new GcmChannel($this->client, $this->events);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $message = $this->notification->toGcm($this->notifiable);

        $title = $message->title;

        $this->client->shouldReceive('send');

        $this->channel->send($this->notifiable, $this->notification);
    }
}
class TestNotifiable
{
    use Notifiable;
}
class TestNotification extends Notification
{
    public function toGcm($notifiable)
    {
        return new GcmMessage();
    }
}

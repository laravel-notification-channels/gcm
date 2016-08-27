<?php

namespace NotificationChannels\Gcm\Test;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Gcm\GcmChannel;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gcm\GcmMessage;
use PHPUnit_Framework_TestCase;
use Mockery;
use ZendService\Google\Gcm\Client;

class ChannelTest extends PHPUnit_Framework_TestCase
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

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new GcmChannel($this->client, $this->events);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
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

<?php

namespace Fruitcake\NotificationChannels\Gcm\Test;

use NotificationChannels\Gcm\Packet;

class PacketTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_render_the_json_for_android()
    {
        $packet = new Packet();

        $packet->setRegistrationIds(['my-token']);
        $packet->setCollapseKey('my-title');
        $packet->setData([
            'title' => 'My Notification',
            'message' => 'My message',
        ]);

        $this->assertEquals($packet->toJson(), '{"registration_ids":["my-token"],"collapse_key":"my-title","data":{"title":"My Notification","message":"My message"}}');
    }

    /** @test */
    public function it_can_render_the_json_for_ios()
    {
        $packet = new Packet();

        $packet->setRegistrationIds(['my-token']);
        $packet->setCollapseKey('my-title');

        $packet->setNotification([
            'title' => 'My Notification',
            'body' => 'My message',
        ]);

        $this->assertEquals($packet->toJson(), '{"registration_ids":["my-token"],"collapse_key":"my-title","notification":{"title":"My Notification","body":"My message"}}');
    }
}

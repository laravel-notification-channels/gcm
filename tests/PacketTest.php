<?php

namespace NotificationChannels\Gcm\Tests;

use NotificationChannels\Gcm\Packet;

class PacketTest extends TestCase
{
    /** @test */
    public function it_can_render_the_json_for_ios_and_android()
    {
        $packet = new Packet();

        $packet->setRegistrationIds(['my-token']);
        $packet->setCollapseKey('my-title');
        $packet->setData([
            'title' => 'My Notification',
            'message' => 'My message',
        ]);
        $packet->setNotification([
            'title' => 'My Notification',
            'body' => 'My message',
        ]);

        $this->assertEquals($packet->toJson(), '{"registration_ids":["my-token"],"collapse_key":"my-title","data":{"title":"My Notification","message":"My message"},"notification":{"title":"My Notification","body":"My message"}}');
    }
}

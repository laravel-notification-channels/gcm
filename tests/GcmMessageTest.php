<?php

namespace Fruitcake\NotificationChannels\Gcm\Test;

use NotificationChannels\Gcm\GcmMessage;

class GcmMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var GcmMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new GcmMessage();
    }

    /** @test */
    public function it_can_accept_parameters_when_constructing_a_message()
    {
        $message = new GcmMessage('myTitle', 'myMessage', ['foo' => 'bar'], GcmMessage::PRIORITY_HIGH);
        $this->assertEquals('myTitle', $message->title);
        $this->assertEquals('myMessage', $message->message);
        $this->assertEquals('bar', $message->data['foo']);
        $this->assertEquals(GcmMessage::PRIORITY_HIGH, $message->priority);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = GcmMessage::create('myTitle', 'myMessage', ['foo' => 'bar'], GcmMessage::PRIORITY_HIGH);
        $this->assertEquals('myTitle', $message->title);
        $this->assertEquals('myMessage', $message->message);
        $this->assertEquals('bar', $message->data['foo']);
        $this->assertEquals(GcmMessage::PRIORITY_HIGH, $message->priority);
    }

    /** @test */
    public function it_can_set_the_title()
    {
        $this->message->title('myTitle');
        $this->assertEquals('myTitle', $this->message->title);
    }

    /** @test */
    public function it_can_set_the_message()
    {
        $this->message->message('myMessage');
        $this->assertEquals('myMessage', $this->message->message);
    }

    /** @test */
    public function it_can_set_data()
    {
        $this->message->data('foo', 'bar');
        $this->assertEquals('bar', $this->message->data['foo']);
    }

    /** @test */
    public function it_has_default_priority()
    {
        $this->assertEquals(GcmMessage::PRIORITY_NORMAL, $this->message->priority);
    }

    /** @test */
    public function it_can_set_the_priority()
    {
        $this->message->priority(GcmMessage::PRIORITY_HIGH);
        $this->assertEquals(GcmMessage::PRIORITY_HIGH, $this->message->priority);
    }

    /** @test */
    public function it_can_set_the_os()
    {
        $this->message->os(GcmMessage::IOS);
        $this->assertEquals(GcmMessage::IOS, $this->message->os);
    }
}

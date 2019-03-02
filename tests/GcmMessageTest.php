<?php

namespace NotificationChannels\Gcm\Tests;

use NotificationChannels\Gcm\GcmMessage;

class GcmMessageTest extends TestCase
{
    /** @var GcmMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();

        $this->message = new GcmMessage();
    }

    /** @test */
    public function it_can_accept_parameters_when_constructing_a_message()
    {
        $message = new GcmMessage('myTitle', 'myMessage', ['foo' => 'bar'], GcmMessage::PRIORITY_HIGH, GcmMessage::DEFAULT_SOUND);
        $this->assertEquals('myTitle', $message->title);
        $this->assertEquals('myMessage', $message->message);
        $this->assertEquals('bar', $message->data['foo']);
        $this->assertEquals(GcmMessage::PRIORITY_HIGH, $message->priority);
        $this->assertEquals(GcmMessage::DEFAULT_SOUND, $message->sound);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = GcmMessage::create('myTitle', 'myMessage', ['foo' => 'bar'], GcmMessage::PRIORITY_HIGH, GcmMessage::DEFAULT_SOUND);
        $this->assertEquals('myTitle', $message->title);
        $this->assertEquals('myMessage', $message->message);
        $this->assertEquals('bar', $message->data['foo']);
        $this->assertEquals(GcmMessage::PRIORITY_HIGH, $message->priority);
        $this->assertEquals(GcmMessage::DEFAULT_SOUND, $message->sound);
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
    public function it_can_set_notification()
    {
        $this->message->notification('foo', 'bar');
        $this->assertEquals('bar', $this->message->notification['foo']);
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
    public function it_has_default_sound()
    {
        $this->assertEquals(GcmMessage::DEFAULT_SOUND, $this->message->sound);
    }

    /** @test */
    public function it_can_set_the_sound()
    {
        $this->message->sound(GcmMessage::DEFAULT_SOUND);
        $this->assertEquals(GcmMessage::DEFAULT_SOUND, $this->message->sound);
    }
}

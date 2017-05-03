<?php

namespace NotificationChannels\Gcm;

class GcmMessage
{
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    const DEFAULT_SOUND = 'default';

    /**
     * The title of the notification.
     *
     * @var string
     */
    public $title;

    /**
     * The message of the notification.
     *
     * @var string
     */
    public $message;

    /**
     * The badge of the notification.
     * @warning UNUSED
     *
     * @var int
     */
    public $badge;

    /**
     * The priority of the notification.
     *
     * @var string
     */
    public $priority = self::PRIORITY_NORMAL;

    /**
     * Notification sound
     *
     * @var string
     */
    public $sound = self::DEFAULT_SOUND;

    /**
     * Additional data of the notification.
     *
     * @var array
     */
    public $data = [];

    /**
     * @param string|null $title
     * @param string|null $message
     * @param array $data
     * @param string $priority
     *
     * @return static
     */
    public static function create($title = null, $message = null, $data = [], $priority = self::PRIORITY_NORMAL, $sound = self::DEFAULT_SOUND)
    {
        return new static($title, $message, $data, $priority, $sound);
    }

    /**
     * @param string|null $title
     * @param string|null $message
     * @param array $data
     * @param string $priority
     * @param string $sound
     */
    public function __construct($title = null, $message = null, $data = [], $priority = self::PRIORITY_NORMAL, $sound = self::DEFAULT_SOUND)
    {
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
        $this->priority = $priority;
        $this->sound = $sound;
    }

    /**
     * Set the title of the notification.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the message of the notification.
     *
     * @param string $message
     *
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the badge of the notification.
     *
     * @param int $badge
     *
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the priority of the notification.
     *
     * @param string $priority
     *
     * @return $this
     */
    public function priority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set the sound for notification
     *
     * @param string $sound
     *
     * @return $this
     */
    public function sound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Add data to the notification.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function data($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Override the data of the notification.
     *
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add an action to the notification.
     *
     * @param string $action
     * @param mixed $params
     *
     * @return $this
     */
    public function action($action, $params = null)
    {
        return $this->data('action', [
            'action' => $action,
            'params' => $params,
        ]);
    }
}

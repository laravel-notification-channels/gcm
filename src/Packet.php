<?php

namespace NotificationChannels\Gcm;

use ZendService\Google\Gcm\Message;
use Zend\Json\Json;

class Packet extends Message
{
    /**
     * @var array
     */
    protected $notification;

    /**
     * Set the notification
     *
     * @param array $ids
     * @return Message
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
        return $this;
    }

    /**
     * To JSON
     * Utility method to put the JSON into the
     * GCM proper format for sending the message.
     *
     * @return string
     */
    public function toJson()
    {
        $json = array();
        if ($this->registrationIds) {
            $json['registration_ids'] = $this->registrationIds;
        }
        if ($this->collapseKey) {
            $json['collapse_key'] = $this->collapseKey;
        }
        if ($this->data) {
            $json['data'] = $this->data;
        }
        if ($this->notification) {
            $json['notification'] = $this->notification;
        }
        if ($this->delayWhileIdle) {
            $json['delay_while_idle'] = $this->delayWhileIdle;
        }
        if ($this->timeToLive != 2419200) {
            $json['time_to_live'] = $this->timeToLive;
        }
        if ($this->restrictedPackageName) {
            $json['restricted_package_name'] = $this->restrictedPackageName;
        }
        if ($this->dryRun) {
            $json['dry_run'] = $this->dryRun;
        }

        return Json::encode($json);
    }
}

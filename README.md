# Laravel GCM (Google Cloud Messaging) Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/gcm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gcm)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/gcm/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/gcm)
[![StyleCI](https://styleci.io/repos/66449457/shield)](https://styleci.io/repos/66449457)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/56e4b8c2-3ca5-4d30-924a-cbe6131faabc.svg?style=flat-square)](https://insight.sensiolabs.com/projects/56e4b8c2-3ca5-4d30-924a-cbe6131faabc)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/gcm.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gcm)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/gcm/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gcm/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/gcm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gcm)

This package makes it easy to send notifications using Google Cloud Messaging (GCM) with Laravel 5.3.

This package is based on [ZendService\Google\Gcm](https://framework.zend.com/manual/2.4/en/modules/zendservice.google.gcm.html), so please read that documentation for more information.


## Contents

- [Installation](#installation)
	- [Setting up the GCM service](#setting-up-the-:service_name-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

    composer require laravel-notification-channels/gcm
    
Register the ServiceProvider in your config/app.php:

    NotificationChannels\Gcm\GcmServiceProvider::class,

### Setting up the GCM service

You need to register for a server key for Google Cloud Messaging for your App in the Google API Console: https://console.cloud.google.com/apis/

Add the API key to your configuration in config/broadcasting.php

    'connections' => [
      ....
      'gcm' => [
          'key' => env('GCM_KEY'),
      ],
      ...
    ]

## Usage

You can now send messages to GCM by creating a GcmMessage:

```php
use NotificationChannels\Gcm\GcmChannel;
use NotificationChannels\Gcm\GcmMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [GcmChannel::class];
    }

    public function toGcm($notifiable)
    {
        return GcmMessage::create()
            ->badge(1)
            ->title('Account approved')
            ->message("Your {$notifiable->service} account was approved!");
    }
}
```

In your `notifiable` model, make sure to include a `routeNotificationForGcm()` method, which return one or an array of tokens.

```php
public function routeNotificationForGcm()
{
    return $this->gcm_token;
}
```

### Available methods

 - title($str)
 - message($str)
 - badge($integer)
 - priority(`GcmMessage::PRIORITY_NORMAL` or `GcmMessage::PRIORITY_HIGH`)
 - data($key, $mixed)
 - action($action, $params) (Will set an `action` data key)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email info@fruitcake.nl instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Fruitcake](https://github.com/fruitcake)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

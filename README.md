# Laravel FCM Notification

![Packagist Version](https://img.shields.io/packagist/v/aliamassi/fcm-notification)
![License](https://img.shields.io/packagist/l/aliamassi/fcm-notification)
![Downloads](https://img.shields.io/packagist/dt/aliamassi/fcm-notification)

Laravel package to send Firebase Cloud Messaging (FCM) notifications to Android and iOS devices.

## Requirements

* PHP 8.0 or higher
* Laravel 8/9/10
* [google/auth](https://packagist.org/packages/google/auth) ^1.20
* [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) ^7.0 fileciteturn1file3

## Installation

```bash
composer require aliamassi/fcm-notification dev-main
```

If you want to publish the configuration file, run:

```bash
php artisan vendor:publish --provider="AliAmassi\FcmNotification\FirebaseNotificationServiceProvider" --tag="config"
```

## Configuration

Copy your Firebase service account JSON to `storage/app/firebase-service-account.json` or set the path in your `.env`:

```dotenv
FIREBASE_CREDENTIALS=/full/path/to/firebase-service-account.json
FIREBASE_PROJECT_ID=your-firebase-project-id
```

The default config values are defined in config/fcm.php. After publishing, you can find the file at config/fcm.php.
## Usage

Use the `FirebaseNotification` facade or inject `AliAmassi\FcmNotification\FirebaseClient`.

### Sending a Single Notification

```php
use FirebaseNotification;

$response = FirebaseNotification::send(
    'device-token',
    [
      'title' => 'Hello',
      'body' => 'This is a test notification',
    ],
    [
      'foo' => 'bar'
    ]
);
```

### Sending to Multiple Devices

```php
$tokens = ['token1', 'token2', 'token3'];

$response = FirebaseNotification::send(
    $tokens,
    ['title' => 'Batch Title', 'body' => 'Batch Body']
);
```

The client implementation handles obtaining and caching the access token via OAuth2 using your service account credentials fileciteturn1file5.

## Publishing to Packagist

1. Tag your release:

   ```bash
   git tag v1.0.0
   git push --tags
   ```

2. Submit your repository on [Packagist](https://packagist.org/packages/submit).

## Contributing

Contributions are welcome! Please submit issues and pull requests.

## License

MIT License. See the [LICENSE](LICENSE) file.

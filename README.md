# Laravel FCM Notification

[![Latest Stable Version](https://img.shields.io/packagist/v/aliamassi/fcm-notification.svg)](https://packagist.org/packages/aliamassi/fcm-notification)
[![Total Downloads](https://img.shields.io/packagist/dt/aliamassi/fcm-notification.svg)](https://packagist.org/packages/aliamassi/fcm-notification)
[![License](https://img.shields.io/packagist/l/aliamassi/fcm-notification.svg)](https://packagist.org/packages/aliamassi/fcm-notification)
[![PHP Version Require](https://img.shields.io/packagist/php-v/aliamassi/fcm-notification.svg)](https://packagist.org/packages/aliamassi/fcm-notification)

A simple and elegant Laravel package to send Firebase Cloud Messaging (FCM) push notifications to Android and iOS devices.

**FCM Endpoint:** `https://fcm.googleapis.com/fcm/send`

---

## ✨ Features

- 🚀 Easy Laravel integration
- 📱 Support for Android & iOS devices
- 🔔 Single & batch notifications
- 📊 Custom data payloads
- ⚙️ Configurable settings
- 🛡️ Built-in error handling

---

## 📋 Requirements

- PHP >= 8.0
- Laravel >= 9.0
- Firebase project with FCM enabled

---

## 📦 Installation

### Step 1: Install Package

#### For Production
```bash
composer require aliamassi/fcm-notification:dev-main --dev
```

#### For Local Development
Add to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "aliamassi/fcm-notification"
        }
    ]
}
```

Then install:
```bash
composer require aliamassi/fcm-notification:dev-main
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --tag=config --provider="AliAmassi\\FcmNotification\\FcmNotificationServiceProvider"
```

### Step 3: Environment Setup

Add to your `.env` file:
```env
FCM_SERVER_KEY=your_firebase_server_key_here
```

---

## ⚙️ Configuration

The published config file (`config/fcm.php`):

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FCM Server Key
    |--------------------------------------------------------------------------
    |
    | Your Firebase Cloud Messaging server key from Firebase Console
    | Project Settings > Cloud Messaging > Server Key
    |
    */
    'server_key' => env('FCM_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | FCM Endpoint
    |--------------------------------------------------------------------------
    |
    | The FCM HTTP v1 API endpoint
    |
    */
    'endpoint' => env('FCM_ENDPOINT', 'https://fcm.googleapis.com/fcm/send'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time in seconds to wait for FCM response
    |
    */
    'timeout' => env('FCM_TIMEOUT', 30),
];
```

---

## 🚀 Usage

### Basic Usage

```php
<?php

use AliAmassi\FcmNotification\FcmNotification;

class NotificationController extends Controller
{
    public function sendNotification(FcmNotification $fcm)
    {
        // Device tokens (single or multiple)
        $tokens = [
            'device_token_1',
            'device_token_2'
        ];

        // Notification payload
        $notification = [
            'title' => 'Hello World!',
            'body'  => 'You have a new message.',
            'sound' => 'default'
        ];

        // Custom data (optional)
        $data = [
            'user_id' => 123,
            'action'  => 'open_chat'
        ];

        try {
            $response = $fcm->send($tokens, $notification, $data);
            
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### Advanced Usage

#### Single Device Token
```php
$token = 'single_device_token';
$response = $fcm->send($token, $notification, $data);
```

#### Rich Notifications
```php
$notification = [
    'title' => 'New Order',
    'body'  => 'You have received a new order #12345',
    'sound' => 'default',
    'icon'  => 'notification_icon',
    'color' => '#FF6B35',
    'click_action' => 'OPEN_ORDER_ACTIVITY'
];
```

#### Custom Options
```php
$options = [
    'priority' => 'high',
    'time_to_live' => 3600,
    'collapse_key' => 'order_updates'
];

$response = $fcm->send($tokens, $notification, $data, $options);
```

---

## 🧪 Testing

### Postman Testing

**Endpoint:** `POST /api/send-notification`

**Headers:**
```
Content-Type: application/json
Authorization: Bearer your_api_token
```

**Request Body:**
```json
{
    "device_tokens": [
        "device_token_1",
        "device_token_2"
    ],
    "notification": {
        "title": "Test Notification",
        "body": "This is a test notification",
        "sound": "default"
    },
    "data": {
        "test_key": "test_value",
        "action": "test_action"
    }
}
```

### cURL Testing

```bash
curl -X POST https://your-app.com/api/send-notification \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token" \
  -d '{
    "device_tokens": ["your_device_token"],
    "notification": {
      "title": "Test",
      "body": "Hello from cURL!"
    },
    "data": {
      "foo": "bar"
    }
  }'
```

---

## 📚 API Reference

### Methods

#### `send($tokens, $notification, $data = [], $options = [])`
Send notification to device tokens.

**Parameters:**
- `$tokens` (string|array) - Device token(s)
- `$notification` (array) - Notification payload
- `$data` (array) - Custom data payload
- `$options` (array) - FCM options

**Returns:** Response array from FCM

### Notification Structure

```php
$notification = [
    'title' => 'string (required)',
    'body'  => 'string (required)',
    'sound' => 'string (optional)',
    'icon'  => 'string (optional)',
    'color' => 'string (optional, #RRGGBB)',
    'click_action' => 'string (optional)',
    'tag'   => 'string (optional)'
];
```

### Options Structure

```php
$options = [
    'priority' => 'normal|high',
    'time_to_live' => 3600, // seconds
    'collapse_key' => 'string',
    'dry_run' => false // boolean
];
```

---

## 🔧 Troubleshooting

### Common Issues

**1. Invalid Server Key**
- Verify `FCM_SERVER_KEY` in `.env`
- Check Firebase Console > Project Settings > Cloud Messaging

**2. Invalid Device Tokens**
- Device tokens expire regularly
- Implement token refresh in your mobile app

**3. Network Issues**
- Ensure server has internet access
- Check firewall settings for HTTPS outbound

**4. Authentication Errors**
- Verify server key format
- Ensure key has FCM permissions

### Debug Mode

Enable Laravel debugging:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

## 📖 Examples

### E-commerce Order Notification

```php
public function sendOrderNotification($order)
{
    $tokens = $order->user->device_tokens;
    
    $notification = [
        'title' => 'Order Confirmed!',
        'body'  => "Your order #{$order->id} has been confirmed",
        'sound' => 'default',
        'icon'  => 'order_icon'
    ];
    
    $data = [
        'order_id' => $order->id,
        'action'   => 'view_order',
        'amount'   => $order->total
    ];
    
    return $this->fcm->send($tokens, $notification, $data);
}
```

### Chat Message Notification

```php
public function sendChatNotification($message)
{
    $tokens = $message->recipient->device_tokens;
    
    $notification = [
        'title' => $message->sender->name,
        'body'  => $message->content,
        'sound' => 'message_sound',
        'click_action' => 'OPEN_CHAT'
    ];
    
    $data = [
        'chat_id' => $message->chat_id,
        'sender_id' => $message->sender_id,
        'message_id' => $message->id
    ];
    
    return $this->fcm->send($tokens, $notification, $data);
}
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/yourusername/laravel-fcm-notification.git`
3. Install dependencies: `composer install`
4. Run tests: `vendor/bin/phpunit`
5. Create feature branch: `git checkout -b feature/amazing-feature`
6. Commit changes: `git commit -m 'Add amazing feature'`
7. Push to branch: `git push origin feature/amazing-feature`
8. Submit Pull Request

---

## 📝 Changelog

### v1.0.0
- Initial release
- Basic FCM notification support
- Single and batch notifications
- Custom data payloads

---

## 📄 License

This package is open-sourced software licensed under the [MIT License](LICENSE).

---

## 🙏 Support

- 📧 **Email:** amassi.business@gmail.com
- 🐛 **Issues:** [GitHub Issues](https://github.com/yourusername/laravel-fcm-notification/issues)
- 📚 **Documentation:** [GitHub Wiki](https://github.com/yourusername/laravel-fcm-notification/wiki)

---

## 🔗 Links

- [Firebase Console](https://console.firebase.google.com/)
- [FCM Documentation](https://firebase.google.com/docs/cloud-messaging)
- [Laravel Documentation](https://laravel.com/docs)

---

**Made with ❤️ for the Laravel community**

# Eglobal One Lab - Whatsapp Integration

## Installation

```bash
composer require eglobal-one-lab/whatsapp-integration
```

## Configuration

Register the service provider
Add the following to your `bootstrap/providers.php` file (Laravel version >= 11):
```php
    EglobalOneLab\WhatsappIntegration\WhatsappIntegrationProvider::class,
```


## Publish config

```bash
php artisan vendor:publish --provider="EglobalOneLab\WhatsappIntegration\WhatsappIntegrationProvider"
```

Edit the `config/whatsapp-integration.php` file to set your configuration.

## Migrations

Run the migrations:

```bash
php artisan migrate
```

## Route usage


There are 2 main routes. One to receive the webhook from twilio and another one to send the history with the assigned phone number.

```bash
POST https:://your-domain.com/whatsapp-integration-webhook
```
To receive messages from twilio

```bash
POST https:://your-domain.com/whatsapp-integration-history
```
To send the history to bitrix


It also receives the parameter ```phone_number```

### Key interceptors

These are keys that must be used from typot, a message is sent with specific keys so that the system can identify it and respond with specific information about it.
![alt text](/assets/interceptors-example.png)

#### Keys

`get_phone_number` : se envía para recibir el número de teléfono del usuario

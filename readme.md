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

It also receives the parameter ```phone_number```, must be in the format +56xxxxxxxxx which must be __exactly the same__ as the one used by the user to contact us.


It is important to send the bitrix history after the negotiation has been created. 

#### To consider

The phone number field must be entered manually, therefore, it is important to make the corresponding checks in typebot so that it is a valid number.

It is possible that if the user enters the wrong number, the history may not load correctly or there may be problems with the association of the negotiation with the corresponding chat.

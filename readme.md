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



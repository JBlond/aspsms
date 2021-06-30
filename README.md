# ASPSMS PHP Client library

For the config copy .env.dist into your root or 
your config folder or add the values to your existing config.
I recommend to use *vlucas/phpdotenv* for reading the data.

This library works with the new API https://webapi.aspsms.com/index.html that is 
a lot faster than the old json interface.

## Examples

```PHP
<?php

use jblond\aspsms\Endpoint;

require 'vendor/autoload.php';

//load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$endpoint = new Endpoint();

print_r($endpoint->getCredits());

print_r($endpoint->sendSMS('Hello You! :)', ['+49555666777'], '2021-06-30T15:20:00+02:00'));

echo '<pre>';
print_r($endpoint->getTrafficStat());
echo '</pre>';

echo '<pre>';
print_r($endpoint->getSendingStat(2021, 6, 1));
echo '</pre>';

echo '<pre>';
print_r($endpoint->getStats());
echo '</pre>';

echo '<pre>';
print_r($endpoint->getPhoneNumbers());
echo '<pre>';
```

### API Endpoints

There are more endpoint to the offical API, but I did not require them.
Feel free to fork my repo add those and open a Pull Request.

## LICENSE

Published under MIT license.

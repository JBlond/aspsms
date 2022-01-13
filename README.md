# ASPSMS PHP Client library

For the config copy .env.dist into your root or 
your config folder or add the values to your existing config.
I recommend to use *vlucas/phpdotenv* for reading the data.

This library works with the new API https://webapi.aspsms.com/index.html that is 
a lot faster than the old json interface.

## install

```bash
composer require jblond/aspsms
```

## Examples

```PHP
<?php

use jblond\aspsms\Endpoint;

require 'vendor/autoload.php';

//load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$endpoint = new Endpoint();
$endpoint->setCredentials($_ENV['KEY'], $_ENV['PASSWORD'], $_ENV['SENDER']);

print_r($endpoint->getCredits());

var_dump($endpoint->sendSMS('Hello You! :)', ['+49555666777'], '2021-06-30T15:20:00+02:00'));

echo '<pre>';
print_r($endpoint->getTrafficStat());
echo '</pre>';

echo '<pre>';
print_r($endpoint->getSendingStat(2021, 6, 1));
echo '</pre>';

// get failed SMS
echo '<pre>';
$report = $endpoint->getSendingStat(date("Y", strtotime("Last month")), date("m", strtotime("Last month")));
foreach ($report as $item){
    if($item['dst'] === 0 || $item['msisdn'] === ''){
        continue;
    }
    print_r($item)
}
echo '</pre>';

echo '<pre>';
print_r($endpoint->getStats());
echo '</pre>';

echo '<pre>';
print_r($endpoint->getPhoneNumbers());
echo '<pre>';
```


sendSMS correct format is Y-m-dTH:i:sP or date("c")

e.g.

```php
$timestamp = date("c", strtotime("+30 seconds"))
```

see [php dateformat](https://www.php.net/manual/de/datetime.format.php)

### config

.env
```dotenv
KEY="loremIPsum"
PASSWORD="verySecret"
SENDER="JBlond"
```

- KEY = your API Key
- PASSWORD = your API password
- SENDER, = sender name that is displayed as send on the phone

### API Endpoints

There are more endpoint to the official API, but I did not require them.
Feel free to fork my repo add those and open a Pull Request.

## LICENSE

Published under MIT license.

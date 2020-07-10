# php-pact
A PHP library for Pact.im API

##installation##
composer require denvolj/pact-im-php

##Include in project##
```php
<?php
require_once('vendor/autoload.php');

use Pact\PactClientBase;`
```

##Usage##
```php
// your top-secret token
$token = '...';

// Your company id
$company = <...>;

// Conversation id
$conversation = <...>;
```

###Client initialization###
`$client = new PactClientBase($token);`

###Receive messages###
`$messages = $client->messages->getMessages($company, $conversation);`

###Attach files###
```php
$fennec_png = __DIR__ . '/fennec.png';
$fun_png = __DIR__ . '/fun.png';

$attach_1 = $client->messages->uploadAttachment($company, $conversation, $fennec_png);
$attach_2 = $client->messages->uploadAttachment($company, $conversation, $fun_png);
```

###Sent file with attachments###
```php
$client->messages->sendMessage($company, $conversation, 'Hello World!', [
    $attach_1->external_id,
    $attach_2->external_id
]);
```

This is experimental API. All may change in future. Do not use in production.

# Channels

## Get all company channels

```php
$channels = $client->channels->getChannels();
```

Result:

```json
{
   "status":"ok",
   "data":{
      "channels":[
         {
            "external_id":399,
            "provider":"whatsapp"
         }
      ],
      "next_page": "fslkfg2lkdfmlwkmlmw4of94wg34lfkm34lg"
   }
}
```

## Create new channel

```php
$companyId = <...>; // Id of company
$provider = <...>;// Provider, for example: 'whatsapp'
$messages = $client->channels->createChannel($companyId, $provider);
```

Result:

```json
{
   "status":"ok",
   "data":{
      "id":18,
      "company_id":154,
      "channel":{
         "id":399,
         "type":"whatsapp"
      },
      "conversation_id":8741,
      "state":"trying_deliver",
      "message_id":null,
      "details":null,
      "created_at":1510396057
   }
}
```

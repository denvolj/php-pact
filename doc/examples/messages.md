# Messages

```php
/** @var int $companyId */
$companyId = <...>;

/** @var int $conversationId */
$conversationId = <...>;
```

## Get all messages from conversation

```php
$messages = $client->messages->getMessages($companyId, $conversationId);
```

Result:

```json
{
   "status":"ok",
   "data":{
      "messages":[
         {
            "external_id":47098,
            "channel_id":381,
            "channel_type":"whatsapp",
            "message":"Hello",
            "income":false,
            "created_at":"2017-09-17T12:44:28.000Z",
            "attachments":[

            ]
         }
      ],
      "next_page": "fslkfg2lkdfmlwkmlmw4of94wg34lfkm34lg"
   }
}
```

## Send message

```php
$msg = 'Hello, World!';
$messages = $client->messages->sendMessage($companyId, $conversationId, $msg);
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

### Send files with attachments

```php
$msg = 'Hello, World!';

$attach_1 = $client->attachmehts->uploadAttachment($company, $conversation, 'fennec.png');
$attach_1 = $client->attachmehts->uploadAttachment(
    $company,
    $conversation,
    'https://upload.wikimedia.org/wikipedia/commons/9/9f/Fennec_Fox_Vulpes_zerda.jpg'
    );
$messages = $client->messages->sendMessage($companyId, $conversationId, $msg, [
    $attach_1->data->external_id,
    $attach_2->data->external_id
]);
```

# Conversation

Conversation represents dialogue between you and the client. Currently only 1-1 conversations are supported. Each conversation has many messages.

```php
/** @var int $companyId */
$companyId = <...>;

/** @var int $conversationId */
$conversationId = <...>;
```

## Get all conversations

```php
$conversations = $client->conversations->getConversations($companyId);
```

Result:

```json
{
   "status":"ok",
   "data":{
      "conversations":[
         {
            "external_id":1,
            "name":"Friend",
            "channel_id":1,
            "channel_type":"whatsapp",
            "created_at":"2017-04-25T18:30:23.076Z",
            "avatar":"/avatars/original/missing.png",
            "sender_external_id":"79260000001",
            "meta":{

            }
         }
      ],
      "next_page": "fslkfg2lkdfmlwkmlmw4of94wg34lfkm34lg"
   }
}
```

## Create new conversation

```php
$provider = 'whatsapp';
$phone = '79250000001';

$conversations = $client->conversations->getConversations($companyId, $provider, $phone);
```

Result:

```json
{
   "status":"ok",
   "data":{
      "conversation":{
         "external_id":1,
         "name":"79250000001",
         "channel_id":1,
         "channel_type":"whatsapp",
         "created_at":"2017-11-11T10:17:10.655Z",
         "avatar":"/avatars/original/missing.png",
         "sender_external_id":"79250000001",
         "meta":{

         }
      }
   }
}
```

## Get conversation details

```php
$conversations = $client->conversations->getDetails($companyId, $conversationId);
```

Result:

```json
{
   "status":"ok",
   "data":{
      "conversation":{
         "external_id":1,
         "name":"79250000001",
         "channel_id":1,
         "channel_type":"whatsapp",
         "created_at":"2017-11-11T10:17:10.655Z",
         "avatar":"/avatars/original/missing.png",
         "sender_external_id":"79250000001",
         "meta":{

         }
      }
   }
}
```

## Update assignee for conversation

```php
// user id
$assigneeId = ...;  
$details = $client->conversations->updateAssignee($company, $conversationId, $assigneeId);
```

### Result

```json
{
   "status":"ok",
   "data":{
      "conversation":{
         "external_id":1,
      }
   }
}
```

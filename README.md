# Api Telegram Manager
> The purpose of this project is to make better management of the [TELEGRAM API](https://core.telegram.org/) providing you with easy use to efficiently develop your Bot.

## Available methods
>[!NOTE]
> More will be added as time goes by

 - [x] sendMessage (sendM)
 - [x] editMessage (editM)
 - [x] Download File
 - [ ] Others
  
## Usage 
>[!TIP]
> These examples are basic, you can adjust them or use them in your own way
```php
# Param number 2 is flag por checker bot token
$objectApiTelegram = new TelegramApi( $botToken, false );


# Example for sendMessage
$objectApiTelegram->sendM( $chatId, "msg" );


# Example for editM
$objectApiTelegram->editM( $chatId, "msg edited" );


# Example for keep File
$objectApiTelegram->downloadFile( $fileId, "myphoto.jgp" );
```

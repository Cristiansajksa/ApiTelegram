<?php
class TelegramApi
{
    private object $objectManegeApiHttps;
    private string $botToken;
    private array $keepParams;
    private int $msgId = 0;


    public function __construct(string $botToken, bool $verifyToken = true) 
    {
        $this->objectManegeApiHttps = new ManegeApiHttps( $botToken );
        $this->botToken = $botToken;
        if ($verifyToken) {
            $this->objectManegeApiHttps->CheckerResult( $this->objectManegeApiHttps->Get("getme"), "getme" );
        }
    }
    
    
    public function sendM(int $chatId, string $msg, int $msgReplit = 0, string $buttonsJson = "") : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "sendMessage", "chat_id=$chatId&text=" . urlencode($msg) . "&reply_to_message_id=$msgReplit&parse_mode=HTML&reply_markup=$buttonsJson" );
        $this->msgId = $this->objectManegeApiHttps->executeRetrys( $objectCurl )->result->message_id;
        curl_close( $objectCurl );
        return $this;
    }


    public function downloadFile(string $fileId, string $nameFileForKeep) : void
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "getFile", "file_id=$fileId" );
        $jsonObject = $this->objectManegeApiHttps->executeRetrys ($objectCurl );
        curl_close( $objectCurl );
        
        file_put_contents( 
            $nameFileForKeep, 
            file_get_contents("https://api.telegram.org/file/bot$this->botToken/{$jsonObject->result->file_path}")
        );
    }



    public function editM(int $chatId, string $msg, int $msgReplit = 0, string $buttonsJson = "") : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "editMessageText", "chat_id=$chatId&text=" . urlencode($msg) . "&message_id={$this->msgId}&parse_mode=HTML&reply_markup=$buttonsJson" );
        curl_close( $objectCurl );
        return $this;
    }
}
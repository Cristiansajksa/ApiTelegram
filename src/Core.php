<?php
class Core
{
    private object $objectManegeApiHttps;
    private string $botToken;
    private array $keepParams;
    private int $msgId = 0;


    public function __construct(string $botToken, bool $verifyToken = true) 
    {
        $this->objectManegeApiHttps = new Api( $botToken );
        $this->botToken = $botToken;
        if ($verifyToken) {
            $this->objectManegeApiHttps->CheckerResult( $this->objectManegeApiHttps->Get("getme"), "getme" );
        }
    }
    
    

    public function sendM(
        int $chatId, string $msg, int $msgReplit = 0, ?string $buttonsJson = "", bool $editMsg = true
    ) : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "sendMessage", "chat_id=$chatId&text=" . urlencode($msg) . "&reply_to_message_id=$msgReplit&parse_mode=HTML&reply_markup=$buttonsJson" );
        $keepDates = $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        $this->msgId = $editMsg ? $keepDates->result->message_id : 0;
        
        curl_close( $objectCurl );
        return $this;
    }



    public function downloadFile(string $fileId, string $nameFileForKeep) : void
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "getFile", "file_id=$fileId" );
        $jsonObject = $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        curl_close( $objectCurl );
        
        file_put_contents( 
            $nameFileForKeep, 
            file_get_contents("https://api.telegram.org/file/bot$this->botToken/{$jsonObject->result->file_path}")
        );
    }



    public function editM(
        int $chatId, string $msg, int $msgId = null, string $buttonsJson = ""
    ) : self
    {
        $msgId = $msgId ?? $this->msgId;
        $objectCurl = $this->objectManegeApiHttps->Get( "editMessageText", "chat_id=$chatId&text=" . urlencode($msg) . "&message_id=$msgId&parse_mode=HTML&reply_markup=$buttonsJson" );
        $this->objectManegeApiHttps->executeRetrys( $objectCurl );

        curl_close( $objectCurl );
        return $this;
    }



    public function alertButton(int $buttonId, string $msg) : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "answerCallbackQuery", "callback_query_id=$buttonId&text=" . urlencode($msg) . "&show_alert=1" );
        $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        curl_close( $objectCurl );
        return $this;
    }



    public function sendD(
        int $chatId, string $msg, string $nameFile, string $jsonButtons = ""
    ) : self
    {
        $postFieldSend = [
            'document' => curl_file_create($nameFile),
            'caption' => $msg,
            'reply_markup' => $jsonButtons,
            "chat_id" => $chatId,
            "parse_mode" => "HTML",
        ];

        $objectCurl = $this->objectManegeApiHttps->post( "sendDocument", $postFieldSend );
        $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        curl_close( $objectCurl );
        return $this;
    }



    public function KickMember(int $chatId, int $userId) : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "kickChatMember", "chat_id=$chatId&user_id=$userId" );
        $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        curl_close( $objectCurl );
        return $this;
    }



    public function UnbanMember(int $chatId, int $userId) : self
    {
        $objectCurl = $this->objectManegeApiHttps->Get( "unbanChatMember", "chat_id=$chatId&user_id=$userId" );
        $this->objectManegeApiHttps->executeRetrys( $objectCurl );
        curl_close( $objectCurl );
        return $this;
    }
    
}

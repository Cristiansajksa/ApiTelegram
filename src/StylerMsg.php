<?php
class StylerMsg implements StylerMsgInterface 
{
    public string $keepButtons = "";
    public string $msgForParse, $msgResult;


    public function __construct(string $msgForParse) 
    {
        $this->msgResult = $this->msgForParse = $msgForParse;
    }



    private function CheckingButtons(array $keepButtons) : string
    {
        foreach ($keepButtons as &$stringButtons) {
            $stringButtons = str_replace( ["(", ")"], "", $stringButtons );
            for ($offSetCount = 0; 2 > $offSetCount; $offSetCount++) {
                $divideInfo = explode( "||", $stringButtons );

                if (stristr($divideInfo[$offSetCount], "http")) {
                    $nameButton = $offSetCount == 0 ? $divideInfo[1] : $divideInfo[0];
                    $keepInfo = ["text" => $nameButton, "url" => $divideInfo[$offSetCount]];
                    break;
                }

                $keepInfo = ["text" => $divideInfo[0], "callback_data" => $divideInfo[1]];
            }
            $stringButtons = $keepInfo;
        }
        return json_encode( ["inline_keyboard" => array_chunk($keepButtons, 2)] );
    }



    public function ParseButtons() : self
    {
        if (preg_match_all("#\\([\S]+\\|\\|[\S]+\\)#", $this->msgForParse, $matchButtons)) {
            $this->msgResult = $this->msgForParse = str_replace( $matchButtons[0], "", $this->msgForParse );
            $this->keepButtons = $this->CheckingButtons( $matchButtons[0] );
        }
        return $this;
    }



    public function ProcessVar() : self 
    {
        if (preg_match_all("#\\$[A-Z]\w+#i", $this->msgForParse, $matchVarsLiterally)) {
            $this->msgResult = $this->msgForParse;

            foreach ($matchVarsLiterally[0] as $varsLiterally) {
                $nameVars = str_replace( "\$", "", $varsLiterally );
                $valueReplace = isset( $GLOBALS[$nameVars] ) ? $GLOBALS[$nameVars] : "";
                $this->msgResult = str_replace( $varsLiterally, $valueReplace, $this->msgResult );
            }
        }
        
        return $this;
    }
}

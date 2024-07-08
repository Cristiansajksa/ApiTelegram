<?php
class StylerMsg implements StylerMsgInterface 
{
    public string $keepButtons = "";
    public string $msgForParse, $msgResult;


    public function __construct(string $msgForParse) 
    {
        $this->msgForParse = $msgForParse;
    }



    private function CheckingButtons(array $keepButtons) : string
    {
        foreach ($keepButtons as &$buttonsIterable) {
            $buttonsIterable = str_replace( ["(", ")"], "", $buttonsIterable );

            for ($offSetCount = 0; 2 > $offSetCount; $offSetCount++) {
                $divideInfo = explode( "||", $buttonsIterable );

                if (!parse_url($divideInfo[$offSetCount])["host"]) {
                    $nameButton = $offSetCount == 0 ? $divideInfo[1] : $divideInfo[0];
                    $buttonsIterable = ["text" => $nameButton, "url" => $divideInfo[$offSetCount]];
                    break;
                }

                $buttonsIterable = ["text" => $divideInfo[0], "callback_data" => $divideInfo[1]];
            }
        }

        return json_encode( ["inline_keyboard" => array_chunk($keepButtons, 2)] );
    }



    public function ParseButtons() : self
    {
        if (preg_match_all("#\\([\S]+\\|\\|[\S]+\\)#", $this->msgForParse, $matchButtons)) {
            $this->msgForParse = str_replace( $matchButtons[0], "", $this->msgForParse );
            $this->keepButtons = $this->CheckingButtons( $matchButtons[0] );
        }
        return $this;
    }



    public function ProcessVar() : self 
    {
        $this->msgResult = $this->msgForParse;
        
        if (preg_match_all("#\\$[A-Z]\w+#i", $this->msgForParse, $matchVarsLiterally)) {
            foreach ($matchVarsLiterally[0] as $varsLiterally) {
                $nameVars = str_replace( "\$", "", $varsLiterally );
                $valueReplace = isset( $GLOBALS[$nameVars] ) ? $GLOBALS[$nameVars] : "";
                $this->msgResult = str_replace( $varsLiterally, $valueReplace, $this->msgResult );
            }
        }
        
        return $this;
    }
}

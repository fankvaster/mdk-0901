<?php

class Form 
{
    public static function Begin($param = [])
    {
       $form = new self;
        
       echo "<form" . $form->getAttr($param) . ">";

       return $form;
    }

    public function input($param = [])
    {
        return "<input {$this->getAttr($param)}>"   ;
    }

    public function password($param = [])
    {
        return "<input {$this->getAttr($param)}>";
    }

    public function textarea($param = [])
    {
        return "<textarea {$this->getAttr($param)}>";
    }

    public function submit($param = [])
    {
        return "<input {$this->getAttr($param)}>";
    }

    public static function end()
    {
        echo "</form>";
    }

    private function getAttr($arr)
    {
        if ($arr) {
            $res = '';
            foreach ($arr as $attr => $val) {
                $res .= $attr . "=" . '"' . $val . '"' . " ";
            }
            return $res;
        }
    }
}
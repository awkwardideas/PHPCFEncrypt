<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class Hex{
    public function hexToBytes($hex){
        $digits = strlen($hex) / 2;

        $bytes= [];
        for($i=0; $i<$digits; $i++){

            $sDigit = substr($hex, ($i*2), 2);

            $bytes[$i] = Byte::Parse(intval($sDigit, 16));
        }

        return $bytes;
    }

    public function bytesToHex($bytes){
        $result="";

        foreach($bytes as $byte){
            $firstDigit = ($byte >> 4) & 0xF;
            $secondDigit = $byte & 0xF;
            $result .= strtoupper(dechex($firstDigit)) . strtoupper(dechex($secondDigit));
        }
        return substr($result,0);
    }

    public function isHexString($string){
        if($string == null){
            return false;
        }

        return ctype_xdigit($string);
    }

    public function isHexDigit($char){
        return ctype_xdigit($char);
    }
}
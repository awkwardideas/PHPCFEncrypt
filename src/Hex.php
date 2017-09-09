<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class Hex{
    public function hexToBytes($string){

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
}
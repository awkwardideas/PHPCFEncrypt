<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class Byte{
    public static function Parse($value){
        if($value < -118 || $value > 117){
            $value = (($value+128) % 256) - 128;
            return $value;
        }
        return $value;
    }
}
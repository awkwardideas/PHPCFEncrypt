<?php
namespace AwkwardIdeas\PHPCFEncrypt;

use AwkwardIdeas\PHPCFEncrypt\Cryptor;
use AwkwardIdeas\PHPCFEncrypt\Hex;
use AwkwardIdeas\PHPCFEncrypt\Exception\InvalidCharacterEncodingException;
use AwkwardIdeas\PHPCFEncrypt\Exception\InvalidEncryptionValException;
use AwkwardIdeas\PHPCFEncrypt\Exception\UnhandledAlgorithmException;
use AwkwardIdeas\PHPCFEncrypt\Exception\UnhandledEncodingTypeException;
use phpseclib3\Crypt\RC4;
use phpseclib3\Crypt\AES;

class Encrypt{
    public static function encrypt($string, $key, $algorithm, $encoding, $prefix=null, $iter=0){
        $charset="UTF-8";

        if(strlen($string)==0){
            throw new InvalidEncryptionValException();
        }

        try{
            iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
            $bytes = array_values(unpack('C*', $string));
        }catch(Exception $e){
            throw new InvalidCharacterEncodingException($e);
        }

        $enc = self::byteEncrypt($bytes, $key, $algorithm, $prefix, $iter);

        return self::binaryEncode($enc, $encoding);

    }

    public static function decrypt($string, $key, $algorithm, $encoding, $prefix=null, $iter=0){
        $charset="UTF-8";

        if(strlen($string)==0){
            throw new InvalidEncryptionValException();
        }

        try {
            //iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
            $bytes = self::binaryDecode($string, $encoding);
            $bytes = self::byteDecrypt($bytes, $key, $algorithm, $prefix, $iter);

            if(is_string($bytes)){
                return $bytes;
            }else{
                return implode(array_map("chr", $bytes));
            }
        }
        catch(Exception $e){
            throw new InvalidCharacterEncodingException($e);
        }

    }

    public static function binaryDecode($string, $encoding){
        switch(strtolower($encoding)){
            case "hex":
                $hex = new Hex();
                return $hex->hexToBytes($string);
                break;
            case "base64":
                $base64 = base64_decode($string);
                return unpack('C*', $base64);
            default:
                throw new UnhandledEncodingTypeException();
        }
    }

    public static function binaryEncode($enc, $encoding){
        switch(strtolower($encoding)){
            case "hex":
                //$chars = array_values(array_map("chr", $enc));
                $hex = new Hex();
                return $hex->bytesToHex($enc);
                break;
            case "base64":
                return base64_encode($enc);
            default:
                throw new UnhandledEncodingTypeException();
        }
    }

    public static function byteEncrypt($bytes, $key, $algorithm, $prefix, $iter){
        $enc=null;

        switch(strtolower($algorithm)){
            case "cfmx_compat":
                $cryptor = new Cryptor();
                $enc = $cryptor->transformString($key, $bytes);
                return $enc;
            case "rc4":
                $rc4 = new RC4();
                $rc4->setKey(base64_decode($key));
                $string = call_user_func_array("pack", array_merge(array("C*"), $bytes));
                $enc = $rc4->encrypt($string);
                return $enc;
            case "aes":
                $aes = new AES('ecb');
                $aes->setKey(base64_decode($key));
                $string = call_user_func_array("pack", array_merge(array("C*"), $bytes));
                $enc = $aes->encrypt($string);
                return $enc;
            default:
                throw new UnhandledAlgorithmException();
        }
    }

    public static function byteDecrypt($bytes, $key, $algorithm, $prefix, $iter)
    {
        $decrypted=null;

        switch(strtolower($algorithm)){
            case "cfmx_compat":
                $cryptor = new Cryptor();
                $decrypted = $cryptor->transformString($key, $bytes);
                return $decrypted;
            case "rc4":
                $rc4 = new RC4();
                $rc4->setKey(base64_decode($key));
                $string = call_user_func_array("pack", array_merge(array("C*"), $bytes));
                $decrypted = $rc4->decrypt($string);
                return $decrypted;
            case "aes":
                $aes = new AES('ecb');
                $aes->setKey(base64_decode($key));
                $string = call_user_func_array("pack", array_merge(array("C*"), $bytes));
                $decrypted = $aes->decrypt($string);
                return $decrypted;
            default:
                throw new UnhandledAlgorithmException();
        }
    }

    public static function stringToBytes($string){
        $bytes = array();

        foreach(str_split($string) as $char){
            $bytes[] = sprintf("%08b", ord($char));
        }
        return $bytes;
    }
}
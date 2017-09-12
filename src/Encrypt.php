<?php
namespace AwkwardIdeas\PHPCFEncrypt;

use AwkwardIdeas\PHPCFEncrypt\Cryptor;
use AwkwardIdeas\PHPCFEncrypt\Hex;
use AwkwardIdeas\PHPCFEncrypt\Exception\InvalidCharacterEncodingException;
use AwkwardIdeas\PHPCFEncrypt\Exception\InvalidEncryptionValException;
use AwkwardIdeas\PHPCFEncrypt\Exception\UnhandledAlgorithmException;
use AwkwardIdeas\PHPCFEncrypt\Exception\UnhandledEncodingTypeException;

class Encrypt{
    public function encrypt($string, $key, $algorithm, $encoding, $prefix=null, $iter=0){
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

        $enc = $this->byteEncrypt($bytes, $key, $algorithm, $prefix, $iter);

        return $this->binaryEncode($enc, $encoding);

    }

    public function decrypt($string, $key, $algorithm, $encoding, $prefix=null, $iter=0){
        $charset="UTF-8";

        if(strlen($string)==0){
            throw new InvalidEncryptionValException();
        }

        try {
            iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
            $bytes = $this->binaryDecode($string, $encoding);

            $bytes = $this->byteDecrypt($bytes, $key, $algorithm, $prefix, $iter);

            return implode(array_map("chr", $bytes));
        }
        catch(Exception $e){
            throw new InvalidCharacterEncodingException($e);
        }

    }

    public function binaryDecode($string, $encoding){
        switch(strtolower($encoding)){
            case "hex":
                $hex = new Hex();
                return $hex->hexToBytes($string);
                break;
            case "base64":
                return base64_decode($string);
            default:
                throw new UnhandledEncodingTypeException();
        }
    }

    public function binaryEncode($enc, $encoding){
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

    public function byteEncrypt($bytes, $key, $algorithm, $prefix, $iter){
        $enc=null;

        if(strtolower($algorithm) === strtolower("CFMX_COMPAT")){
            $cryptor = new Cryptor();
            $enc = $cryptor->transformString($key, $bytes);
            return $enc;
        }else{
            throw new UnhandledAlgorithmException();
        }
    }

    public function byteDecrypt($bytes, $key, $algorithm, $prefix, $iter)
    {
        $decrypted="";

        if(strtolower($algorithm) === strtolower("CFMX_COMPAT")){
            $cryptor = new Cryptor();
            $enc = $cryptor->transformString($key, $bytes);
            return $enc;
        }else{
            throw new UnhandledAlgorithmException();
        }
    }
}
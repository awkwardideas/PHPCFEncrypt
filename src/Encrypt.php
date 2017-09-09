<?php
namespace AwkwardIdeas\PHPCFEncrypt;

use AwkwardIdeas\PHPCFEncrypt\Cryptor;
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

//    static byte[] encrypt(byte[] bytes, String key, String algorithm, byte[] prefix, int iter)
//    {
//        byte[] enc;
//        byte[] enc;
//        if (algorithm.equalsIgnoreCase("CFMX_COMPAT"))
//        {
//            Cryptor cryptor = new Cryptor();
//            enc = cryptor.transformString(key, bytes);
//        }
//        else
//            {
//                try
//                {
//                    enc = processCipherWork(bytes, null, key, algorithm, 1, prefix, iter);
//                }
//                catch (NoSuchAlgorithmException nsae)
//              {
//                  if (algorithm.equalsIgnoreCase("DESede"))
//                  {
//
//
//
//
//
//
//                      registerSunCryptoProvider();
//
//                      try
//                      {
//                          enc = processCipherWork(bytes, null, key, algorithm, 1, prefix, iter);
//                      }
//                      catch (NoSuchAlgorithmException e)
//                  {
//                      throw new InvalidAlgorithmException(algorithm);
//                  }
//
//                }
//                  else
//                  {
//                      throw new InvalidAlgorithmException(algorithm);
//                  }
//              }
//              catch (ExpressionException e)
//              {
//                  throw e;
//              }
//            }
//        return enc;
//    }

//    private function CFMX_COMPAT($string, $key, $prefix, $iter){
//        Cryptor $cryptor = new Cryptor();
//        $enc = $cryptor.transformString($key, $bytes);
//    }


}
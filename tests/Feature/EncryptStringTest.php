<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use AwkwardIdeas\PHPCFEncrypt\Cryptor;
use AwkwardIdeas\PHPCFEncrypt\Hex;
use PHPUnit\Framework\TestCase;

class EncryptStringTest extends TestCase
{

    public function testEncryptedStringMatchesColdfusionEncryption(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "650C948E904A";

        $encrypt = new Encrypt();
        $encoded = $encrypt->encrypt($password, $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypt_returns_string(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "650C948E904A";

        $encrypt = new Encrypt();
        $encoded = $encrypt->encrypt($password,$key,"CFMX_COMPAT","hex");

        $this->assertTrue(is_string($encoded), "Got a " . gettype($encoded) . " instead of a string" );
    }

    public function test_get_bytes(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = [116,101,115,116,105,116];

        $bytes = array_values(unpack('C*', $password));

        $this->assertEquals($expected, $bytes);
    }

    public function test_get_bytes_encrypted(){
        $password = "testit";
        $key = "thisisatestkey";
        $bytes = [116,101,115,116,105,116];
        $algorithm="CFMX_COMPAT";
        $prefix="";
        $iter="";

        $expected = [101,12,-108,-114,-112,74];

        $encrypt = new Encrypt();
        $enc = $encrypt->byteEncrypt($bytes, $key, $algorithm, $prefix, $iter);

        $this->assertEquals($expected, $enc);
    }

    public function test_transform_string(){
        $bytes = [116,101,115,116,105,116];
        $password = "testit";
        $key = "thisisatestkey";

        $expected = [101,12,-108,-114,-112,74];

        $cryptor = new Cryptor();
        $enc = $cryptor->transformString($key, $bytes);

        $this->assertEquals($expected, $enc);
    }

    public function test_bytes_to_hex(){
        $expected = "650C948E904A";
        $bytes = [101,12,-108,-114,-112,74];
        $hex = new Hex();
        $result = $hex->bytesToHex($bytes);

        $this->assertEquals($expected, $result);
    }
}
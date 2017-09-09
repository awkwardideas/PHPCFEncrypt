<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class StringToBytesTest extends TestCase
{
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
}
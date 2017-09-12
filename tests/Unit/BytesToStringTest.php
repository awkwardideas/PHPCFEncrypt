<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class BytesToStringTest extends TestCase
{
    public function test_get_string(){
        $passwordBytes = [116,101,115,116,105,116];
        $expected = "testit";

        $string = implode(array_map("chr", $passwordBytes));

        $this->assertEquals($expected, $string);
    }

    public function test_get_bytes_decrypted(){
        $bytes = [101,12,-108,-114,-112,74];
        $key = "thisisatestkey";
        $algorithm="CFMX_COMPAT";
        $prefix="";
        $iter="";

        $expected = [116,101,115,116,105,116];

        $enc = Encrypt::byteDecrypt($bytes, $key, $algorithm, $prefix, $iter);

        $this->assertEquals($expected, $enc);
    }
}
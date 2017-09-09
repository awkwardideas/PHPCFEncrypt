<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Cryptor;
use PHPUnit\Framework\TestCase;

class CryptorTest extends TestCase
{

    public function test_transform_string()
    {
        $bytes = [116, 101, 115, 116, 105, 116];
        $password = "testit";
        $key = "thisisatestkey";

        $expected = [101, 12, -108, -114, -112, 74];

        $cryptor = new Cryptor();
        $enc = $cryptor->transformString($key, $bytes);

        $this->assertEquals($expected, $enc);
    }
}
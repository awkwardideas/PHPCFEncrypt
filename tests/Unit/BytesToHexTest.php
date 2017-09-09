<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Hex;
use PHPUnit\Framework\TestCase;

class BytesToHexTest extends TestCase
{
    public function test_bytes_to_hex(){
        $expected = "650C948E904A";
        $bytes = [101,12,-108,-114,-112,74];
        $hex = new Hex();
        $result = $hex->bytesToHex($bytes);

        $this->assertEquals($expected, $result);
    }
}
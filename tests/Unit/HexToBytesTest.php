<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Hex;
use PHPUnit\Framework\TestCase;

class HexToBytesTest extends TestCase
{
    public function test_bytes_to_hex(){
        $expected = [101,12,-108,-114,-112,74];
        $hexStart = "650C948E904A";
        $bytes = Hex::hexToBytes($hexStart);

        $this->assertEquals($expected, $bytes);
    }
}
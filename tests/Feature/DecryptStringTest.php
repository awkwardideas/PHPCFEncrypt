<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class DecryptStringTest extends TestCase
{
    public function test_encrypted_string_matches_coldfusion_encryption(){
        $password = "650C948E904A";
        $key = "thisisatestkey";
        $expected = "testit";

        $encrypt = new Encrypt();
        $encoded = $encrypt->decrypt($password, $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypt_returns_string(){
        $password = "650C948E904A";
        $key = "thisisatestkey";
        $expected = "testit";

        $encrypt = new Encrypt();
        $encoded = $encrypt->decrypt($password,$key,"CFMX_COMPAT","hex");

        $this->assertTrue(is_string($encoded), "Got a " . gettype($encoded) . " instead of a string" );
    }
}
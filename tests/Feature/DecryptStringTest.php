<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class DecryptStringTest extends TestCase
{
    public function test_encrypted_string_matches_coldfusion_encryption(){
        $password = "588081DFB092";
        $key = "pipelinesuite`1";
        $expected = "testit";

        $encoded = Encrypt::decrypt($password, $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypt_returns_string(){
        $password = "650C948E904A";
        $key = "thisisatestkey";
        $expected = "testit";

        $encoded = Encrypt::decrypt($password,$key,"CFMX_COMPAT","hex");

        $this->assertTrue(is_string($encoded), "Got a " . gettype($encoded) . " instead of a string" );
    }
}
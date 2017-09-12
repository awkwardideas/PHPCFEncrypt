<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class EncryptDecryptStringTest extends TestCase
{
    public function test_encrypted_string_decrypted_matches_origin(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "testit";

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "CFMX_COMPAT", "hex"), $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $encodedDecoded);
    }

    public function test_encrypt_decrypt_returns_string(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "testit";

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "CFMX_COMPAT", "hex"), $key, "CFMX_COMPAT", "hex");

        $this->assertTrue(is_string($encodedDecoded), "Got a " . gettype($encodedDecoded) . " instead of a string" );
    }
}
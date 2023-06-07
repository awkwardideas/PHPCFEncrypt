<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class EncryptDecryptStringTest extends TestCase
{
    public function test_encrypted_string_decrypted_matches_origin(){
        $password = "testit";
        $key = "thisisatestkey";

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "CFMX_COMPAT", "hex"), $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($password, $encodedDecoded);
    }

    public function test_encrypt_decrypt_returns_string(){
        $password = "testit";
        $key = "thisisatestkey";

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "CFMX_COMPAT", "hex"), $key, "CFMX_COMPAT", "hex");

        $this->assertTrue(is_string($encodedDecoded), "Got a " . gettype($encodedDecoded) . " instead of a string" );
    }

    public function test_encrypted_rc4_string_decrypted_matches_origin(){
        $password = "testit";
        $key = base64_encode("thisisatestkey");

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "RC4", "base64"), $key, "RC4", "base64");

        $this->assertEquals($password, $encodedDecoded);
    }

    public function test_encrypted_aes_string_decrypted_matches_origin(){
        $password = "testit";
        $key = base64_encode("thisisatestkey16"); //requires 16 length key

        $encodedDecoded = Encrypt::decrypt(Encrypt::encrypt($password, $key, "AES", "base64"), $key, "AES", "base64");

        $this->assertEquals($password, $encodedDecoded);
    }
}
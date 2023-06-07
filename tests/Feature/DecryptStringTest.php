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

        $decoded = Encrypt::decrypt($password, $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $decoded);
    }

    public function test_decrypt_returns_string(){
        $password = "650C948E904A";
        $key = "thisisatestkey";
        $expected = "testit";

        $decoded = Encrypt::decrypt($password,$key,"CFMX_COMPAT","hex");

        $this->assertTrue(is_string($decoded), "Got a " . gettype($decoded) . " instead of a string" );
    }

    public function test_encrypted_string_matches_coldfusion_encryption_rc4(){
        $encrypted = "+KZI2TEY";
        $key = base64_encode("thisisatestkey");
        $expected = "testit";

        $decoded = Encrypt::decrypt($encrypted, $key, "RC4", "base64");

        $this->assertEquals($expected, $decoded);
    }

    public function test_decrypt_rc4_returns_string(){
        $encrypted = "+KZI2TEY";
        $key = base64_encode("thisisatestkey");
        $expected = "testit";

        $decoded = Encrypt::decrypt($encrypted, $key, "RC4", "base64");

        $this->assertTrue(is_string($decoded), "Got a " . gettype($decoded) . " instead of a string" );
    }

    public function test_encrypted_string_matches_coldfusion_encryption_aes(){
        $encrypted = "1K83vj6Xd3v7QeS4gq8gwA==";
        $key = "hqg0ZwcHPzkgprfvem9IYQ==";
        $expected = "testit";

        $decoded = Encrypt::decrypt($encrypted, $key, "AES", "base64");

        $this->assertEquals($expected, $decoded);
    }

    public function test_decrypt_aes_returns_string(){
        $encrypted = "1K83vj6Xd3v7QeS4gq8gwA==";
        $key = "hqg0ZwcHPzkgprfvem9IYQ==";
        $expected = "testit";

        $decoded = Encrypt::decrypt($encrypted, $key, "aes", "base64");

        $this->assertTrue(is_string($decoded), "Got a " . gettype($decoded) . " instead of a string" );
    }
}
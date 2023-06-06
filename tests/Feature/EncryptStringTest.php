<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\Encrypt;
use PHPUnit\Framework\TestCase;

class EncryptStringTest extends TestCase
{
    public function test_encrypted_string_matches_coldfusion_encryption(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "650C948E904A";

        $encoded = Encrypt::encrypt($password, $key, "CFMX_COMPAT", "hex");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypt_returns_string(){
        $password = "testit";
        $key = "thisisatestkey";
        $expected = "650C948E904A";

        $encoded = Encrypt::encrypt($password,$key,"CFMX_COMPAT","hex");

        $this->assertTrue(is_string($encoded), "Got a " . gettype($encoded) . " instead of a string" );
    }

    public function test_encrypted_string_matches_coldfusion_rc4_encryption(){
        $password = "testit";
        $key = base64_encode("thisisatestkey");
        $expected = "+KZI2TEY";

        $encoded = Encrypt::encrypt($password, $key, "RC4", "Base64");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypted_rc4_encryption(){
        $password = "1041789.33333";
        $key = "Pv9rW1FoIp6gjvXHI/jETw==";
        $expected = "Myc5hV0/g2phUxEIZg==";

        $encoded = Encrypt::encrypt($password, $key, "RC4", "Base64");
        echo $encoded;
        die;
        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypted_string_matches_coldfusion_aes_encryption(){
        $password = "testit";
        $key = "hqg0ZwcHPzkgprfvem9IYQ==";
        $expected = "1K83vj6Xd3v7QeS4gq8gwA==";

        $encoded = Encrypt::encrypt($password, $key, "AES", "Base64");

        $this->assertEquals($expected, $encoded);
    }

    public function test_encrypted_aes_encryption(){
        $password = "1041789.33333";
        $key = "hqg0ZwcHPzkgprfvem9IYQ==";
        $expected = "Myc5hV0/g2phUxEIZg==";

        $encoded = Encrypt::encrypt($password, $key, "AES", "Base64");
        echo $encoded;
        die;
        $this->assertEquals($expected, $encoded);
    }
}
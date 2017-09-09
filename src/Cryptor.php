<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class Cryptor{

    private $m_Key;

    private $m_LFSR_A = 0x13579bdf;//324508639;
    private $m_LFSR_B = 0x2468ace0;//610839776;
    private $m_LFSR_C = 0xfdb97531;//-38177487;
    private $m_Mask_A = 0x80000062;//-2147483550;
    private $m_Mask_B = 0x40000020;//1073741856;
    private $m_Mask_C = 0x10000002;//268435458;
    private $m_Rot0_A = 0x7fffffff;//2147483647;
    //private $m_Rot0_A = PHP_INT_MAX;
    private $m_Rot0_B = 0x3fffffff;//1073741823;
    private $m_Rot0_C = 0xffffffff;//268435455;
    private $m_Rot1_A = 0x80000000;//-2147483648;
    //private $m_Rot1_A = PHP_INT_MIN;
    private $m_Rot1_B = 0xc0000000;//-1073741824;
    private $m_Rot1_C = 0xf0000000;//-268435456;

    public function __construct(){
//        $this->m_LFSR_A = 324508639;
//        $this->m_LFSR_B = 610839776;
//        $this->m_LFSR_C = -38177487;
//        $this->m_Mask_A = -2147483550;
//        $this->m_Mask_B = 1073741856;
//        $this->m_Mask_C = 268435458;
//        $this->m_Rot0_A = PHP_INT_MAX;
//        $this->m_Rot0_B = 1073741823;
//        $this->m_Rot0_C = 268435455;
//        $this->m_Rot1_A = PHP_INT_MIN;
//        $this->m_Rot1_B = -1073741824;
//        $this->m_Rot1_C = -268435456;
    }

    // $key string in
    // $inBytes array in
    // $outBytes array out
    public function transformString($key, Array $inBytes){
        $this->setKey($key);
        $length = count($inBytes);
        $outBytes = [];
        for ($i = 0; $i < $length; $i++)
        {
            $outBytes[$i] = $this->transformByte($inBytes[$i]);
        }

        return $outBytes;
    }

    private function transformByte($Target){
        $Crypto = 0;
        $Out_B = ($this->m_LFSR_B & 0x1);
        $Out_C = ($this->m_LFSR_C & 0x1);

        $Target = $this->parse_byte($Target);

        for ($i = 0; $i < 8; $i++)
        {
            if (0 != ($this->m_LFSR_A & 1))
            {

                $this->m_LFSR_A = $this->m_LFSR_A ^ $this->m_Mask_A >> 1 | $this->m_Rot1_A;

                if (0 != ($this->m_LFSR_B & 1))
                {
                    $this->m_LFSR_B = $this->m_LFSR_B ^ $this->m_Mask_B >> 1 | $this->m_Rot1_B;
                    $Out_B = 1;
                }
                else{
                    $this->m_LFSR_B = $this->m_LFSR_B >> 1 & $this->m_Rot0_B;
                    $Out_B = 0;
                }
            }
            else
            {
                $this->m_LFSR_A = ($this->m_LFSR_A >> 1) & $this->m_Rot0_A;
                if (0 != ($this->m_LFSR_C & 1))
                {
                    $this->m_LFSR_C = $this->m_LFSR_C ^ $this->m_Mask_C >> 1 | $this->m_Rot1_C;
                    $Out_C = 1;
                }
                else
                {
                    $this->m_LFSR_C = $this->m_LFSR_C >> 1 & $this->m_Rot0_C;
                    $Out_C = 0;
                }
            }
            $Crypto = $this->parse_byte(($Crypto << 1 | $Out_B ^ $Out_C));
        }
        $Target ^= $Crypto;

        return $Target;
    }

    private function parse_byte($value){
        if($value < -118 || $value > 117){
            $value = (($value+128) % 256) - 128;
            return $value;
        }
        return $value;
    }

    private function setKey($Key)
    {
        $Index = 0;

        if (0 == strlen($Key))
        {
            $Key = "Default Seed";
        }
        $this->m_Key = $Key;

        $Seed=array_values(unpack('C*', $this->m_Key));

        $originalLength = strlen($this->m_Key);

        for ($i = 0; ($originalLength + $i) < 12; $i++)
        {
            $Seed[($originalLength + $i)] = $Seed[$i];
        }

        for ($i = 0; $i < 4; $i++)
        {
            $this->m_LFSR_A = ($this->m_LFSR_A <<= 8) | $Seed[$i + 4];
            $this->m_LFSR_B = ($this->m_LFSR_B <<= 8) | $Seed[$i + 4];
            $this->m_LFSR_C = ($this->m_LFSR_C <<= 8) | $Seed[$i + 4];
        }

        if (0 == $this->m_LFSR_A) $this->m_LFSR_A =     0x13579bdf;//324508639;
        if (0 == $this->m_LFSR_B) $this-> m_LFSR_B =    0x2468ace0;//610839776;
        if (0 == $this->m_LFSR_C) $this->m_LFSR_C =     0xfdb97531;//-38177487;

    }
}
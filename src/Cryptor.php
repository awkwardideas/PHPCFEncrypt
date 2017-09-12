<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class Cryptor{

    private $m_Key;

    private $m_LFSR_A = 0x13579bdf;
    private $m_LFSR_B = 0x2468ace0;
    private $m_LFSR_C = 0xfdb97531;
    private $m_Mask_A = 0x80000062;
    private $m_Mask_B = 0x40000020;
    private $m_Mask_C = 0x10000002;
    private $m_Rot0_A = 0x7fffffff;
    private $m_Rot0_B = 0x3fffffff;
    private $m_Rot0_C = 0xffffffff;
    private $m_Rot1_A = 0x80000000;
    private $m_Rot1_B = 0xc0000000;
    private $m_Rot1_C = 0xf0000000;

    public function __construct(){

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

        $Target = Byte::Parse($Target);

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
            $Crypto = Byte::Parse(($Crypto << 1 | $Out_B ^ $Out_C));
        }
        $Target ^= $Crypto;

        return $Target;
    }

    private function setKey($Key)
    {
        if (0 == strlen($Key)) $Key = "Default Seed";

        $this->m_Key = $Key;

        $Seed=array_values(unpack('C*', $this->m_Key));

        $originalLength = strlen($this->m_Key);

        for ($i = 0; ($originalLength + $i) < 12; $i++)
        {
            $Seed[$originalLength + $i] = $Seed[$i];
        }

        for ($i = 0; $i < 4; $i++)
        {
            $this->m_LFSR_A = ($this->m_LFSR_A <<= 8) | $Seed[$i + 4];
            $this->m_LFSR_B = ($this->m_LFSR_B <<= 8) | $Seed[$i + 4];
            $this->m_LFSR_C = ($this->m_LFSR_C <<= 8) | $Seed[$i + 4];
        }

        if (0 == $this->m_LFSR_A) $this->m_LFSR_A =     0x13579bdf;
        if (0 == $this->m_LFSR_B) $this-> m_LFSR_B =    0x2468ace0;
        if (0 == $this->m_LFSR_C) $this->m_LFSR_C =     0xfdb97531;

    }
}
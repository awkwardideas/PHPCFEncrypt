<?php
namespace AwkwardIdeas\PHPCFEncrypt;

use AwkwardIdeas\PHPCFEncrypt\BitwiseOperators as Bit;

class Cryptor{

    private $m_Key;

    private $m_LFSR_A = 324508639;//0x13579bdf;
    private $m_LFSR_B = 610839776;//0x2468ace0;
    private $m_LFSR_C = -38177487;//0xfdb97531;
    private $m_Mask_A = -2147483550;//0x80000062;
    private $m_Mask_B = 1073741856;//0x40000020;
    private $m_Mask_C = 268435458;//0x10000002;
    private $m_Rot0_A = 2147483647;//0x7fffffff;
    private $m_Rot0_B = 1073741823;//0x3fffffff;
    private $m_Rot0_C = 268435455;//0xffffffff;
    private $m_Rot1_A = -2147483648;//0x80000000;
    private $m_Rot1_B = -1073741824;//0xc0000000;
    private $m_Rot1_C = -268435456;//0xf0000000;

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
        $Out_B = Bit::And($this->m_LFSR_B, 1);
        $Out_C = Bit::And($this->m_LFSR_C, 1);

        $Target = Byte::Parse($Target);

        for ($i = 0; $i < 8; $i++)
        {
            if (0 != Bit::And($this->m_LFSR_A, 1))
            {

                $test= Bit::ShiftRight($this->m_Mask_A, 1);
                $test2 = Bit::Xor($this->m_LFSR_A, $test);
                $test3 = Bit::Or($test2, $this->m_Rot1_A);

                $this->m_LFSR_A = Bit::Or(
                                    Bit::Xor(
                                        $this->m_LFSR_A,
                                        Bit::ShiftRight($this->m_Mask_A, 1)
                                    ),
                                    $this->m_Rot1_A
                                  );

                if (0 != Bit::And($this->m_LFSR_B, 1))
                {
                    $this->m_LFSR_B = Bit::Or(
                                        Bit::Xor(
                                            Bit::ShiftRight($this->m_Mask_B, 1),
                                            $this->m_LFSR_B
                                        ),
                                        $this->m_Rot1_B
                                      );
                    $Out_B = 1;
                }
                else{
                    $this->m_LFSR_B = Bit::And(
                                        Bit::ShiftRight($this->m_LFSR_B, 1),
                                        $this->m_Rot0_B
                                      );
                    $Out_B = 0;
                }
            }
            else
            {
                $this->m_LFSR_A = Bit::And(
                                    Bit::ShiftRight($this->m_LFSR_A, 1),
                                    $this->m_Rot0_A
                                  );
                if (0 != Bit::And($this->m_LFSR_C, 1))
                {
                    $this->m_LFSR_C = Bit::Or(
                                        Bit::Xor(
                                            Bit::ShiftRight($this->m_Mask_C,1),
                                            $this->m_LFSR_C
                                        ),
                                        $this->m_Rot1_C
                                      );
                    $Out_C = 1;
                }
                else
                {
                    $this->m_LFSR_C = Bit::And(
                                        Bit::ShiftRight($this->m_LFSR_C, 1),
                                        $this->m_Rot0_C
                                      );
                    $Out_C = 0;
                }
            }
            $Crypto = Byte::Parse(Bit::Or(
                        Bit::ShiftLeft($Crypto, 1),
                        Bit::Xor($Out_B, $Out_C)
                      ));
        }
        $Target = Byte::Parse(Bit::Xor($Target, $Crypto));

        return $Target;
    }

    private function setKey($Key)
    {
        if (0 == strlen($Key)) $Key = "Default Seed";

        $this->m_Key = $Key;

        $Seed=str_split($this->m_Key);//array_values(unpack('C*', $this->m_Key));

        $originalLength = strlen($this->m_Key);

        for ($i = 0; ($originalLength + $i) < 12; $i++)
        {
            $Seed[$originalLength + $i] = $Seed[$i];
        }

        for ($i = 0; $i < 4; $i++)
        {
            $seedOrValue=ord($Seed[$i + 4]);
            $this->m_LFSR_A = Bit::ShiftLeft($this->m_LFSR_A, 8);
            $this->m_LFSR_A = Bit::Or($this->m_LFSR_A, $seedOrValue);

            $this->m_LFSR_B = Bit::ShiftLeft($this->m_LFSR_B, 8);
            $this->m_LFSR_B = Bit::Or($this->m_LFSR_B, $seedOrValue);

            $this->m_LFSR_C = Bit::ShiftLeft($this->m_LFSR_C, 8);
            $this->m_LFSR_C = Bit::Or($this->m_LFSR_C, $seedOrValue);
        }

        if (0 == $this->m_LFSR_A) $this->m_LFSR_A =     324508639;//0x13579bdf;
        if (0 == $this->m_LFSR_B) $this-> m_LFSR_B =    610839776;//0x2468ace0;
        if (0 == $this->m_LFSR_C) $this->m_LFSR_C =     -38177487;//0xfdb97531;

    }
}
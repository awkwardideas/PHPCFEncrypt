<?php
namespace AwkwardIdeas\PHPCFEncrypt;

class BitwiseOperators{
    public static function ShiftLeft($value, $steps){
        $bits = self::Int32Bin($value);
        $bitLength = strlen($bits);
        $bits .= str_repeat(0, $steps);
        $bits = substr($bits, -32);
        return self::BinInt32($bits);
    }

    public static function ShiftRight($value, $steps){
        $bits = self::Int32Bin($value);
        $significantBit = substr($bits, 0,1);
        $bits = str_repeat($significantBit, $steps) . $bits;
        $bits = substr($bits, 0, 32);
        return self::BinInt32($bits);
    }

    public static function LogicalShiftRight($value, $steps){
        $bits = self::Int32Bin($value);
        $bits = str_repeat(0, $steps) . $bits;
        $bits = substr($bits, 0, 32);
        return self::BinInt32($bits);
    }

    public static function Or($value, $value2){
        $bits = self::Int32BitArray($value);
        $bits2 = self::Int32BitArray($value2);

        $value = array();
        for($i=0;$i<32;$i++){
            $value[$i] = $bits[$i] | $bits2[$i];
        }

        return self::BitArrayInt32($value);
    }

    public static function Xor($value, $value2){
        $bits = self::Int32BitArray($value);
        $bits2 = self::Int32BitArray($value2);

        $value = array();
        for($i=0;$i<32;$i++){
            $value[$i] = $bits[$i] ^ $bits2[$i];
        }

        return self::BitArrayInt32($value);
    }

    public static function And($value, $value2){
        $bits = self::Int32BitArray($value);
        $bits2 = self::Int32BitArray($value2);

        $value = array();
        for($i=0;$i<32;$i++){
            $value[$i] = $bits[$i] & $bits2[$i];
        }

        return self::BitArrayInt32($value);
    }

    public static function Not($value){
        $bits = self::Int32BitArray($value);

        $value = array();
        for($i=0;$i<32;$i++){
            $value[$i] = ($bits[$i] == 0) ? 1 : 0;
        }

        return self::BitArrayInt32($value);
    }

    public static function Int32($value){
        //Checks if current system is 32 bit. 1<<32 is 0x100000000 on 64 bit or higher and 0 on 32 and lower
        if((1<<32)===0){
            //Just cast to Int32
            return (int)$value;
        }
        //Manually cast to Int32
        $value= $value & 0xFFFFFFFF; //strip larger bits
        return $value<=0x7FFFFFFF?$value:$value | (4294967295 << 32); //properly treat sign bit
    }

    public static function Int32Bin($int){
        return implode(self::Int32BitArray($int));
    }

    public static function Int32BitArray($int){
        $int = self::Int32($int);
        $intMax = 2147483647;
        $intMin = -2147483648;

        if($int==$intMax){
            return [
                0,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1
            ];
        }

        if($int==$intMin){
            return [
                1,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,
            ];
        }

        if($int==0){
            return [
                0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0
            ];
        }

        $bits = array();
        $bits[0] = ($int >= 0) ? 0 : 1;

        $intRemain = abs($int);
        for($i=1; $i<32; $i++) {
            $bitTest = pow(2, 31 - $i);
            if($bitTest > abs($int)){
                $bits[$i] = 0;

                continue;
            }
            $byteValue = ($intRemain / $bitTest) >= 1 ? 1 : 0;

            if($byteValue === 1){
                $intRemain -= $bitTest;
            }

            $bits[$i] = $byteValue;

        }

        $bits = self::InvertBitsAddOne($bits);

        return $bits;
    }

    public static function BinInt32($bits)
    {
        $bits = str_split($bits);
        return self::BitArrayInt32($bits);
    }

    public static function BitArrayInt32($bits)
    {
        $bits = self::InvertBitsAddOne($bits);

        $int = 0;
        for($i=31; $i>0; $i--) {
            if($bits[$i] == 0){
                continue;
            }
            $int += pow(2, 31 - $i);
        }

        if($bits[0] == 1){
            $int *=-1;
        }
        return $int;

    }

    public static function InvertBitsAddOne($bits){
        if($bits[0] == 0){
            return $bits;
        }
        $addOne=true;
        for($i=31; $i>0; $i--) {
            $bits[$i] = ($bits[$i]==1) ? 0 : 1;
            if($addOne===true){
                if($bits[$i]===0){
                    $bits[$i]=1;
                    $addOne=false;
                }else{
                    $bits[$i]=0;
                }
            }
        }
        return $bits;
    }
}
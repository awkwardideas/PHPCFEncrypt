<?php
namespace AwkwardIdeas\PHPCFEncrypt\Tests\Feature;

use AwkwardIdeas\PHPCFEncrypt\BitwiseOperators as Bit;
use PHPUnit\Framework\TestCase;

class BitwiseOperatorTest extends TestCase
{
    public function test_int32_binary_string(){
        $expected = "11111111111111111111111111110100";
        $start = -12;

        $result = Bit::Int32Bin($start);
        $this->assertEquals($expected, $result);
    }

    public function test_binary_string_to_int32(){
        $expected = -12;
        $start = "11111111111111111111111111110100";

        $result = Bit::BinInt32($start);
        $this->assertEquals($expected, $result);
    }

    public function test_left_shift(){
        $expected = -307200;
        $start =  -1200;
        $shift = 8;

        $result = Bit::ShiftLeft($start, $shift);

        $this->assertEquals($expected, $result);
    }

    public function test_right_shift(){
        $expected = -5;
        $start =  -1200;
        $steps = 8;

        $result = Bit::ShiftRight($start, $steps);

        $this->assertEquals($expected, $result);
    }

    public function test_right_arithmetic_shift(){
        $expected = 16777211;
        $start =  -1200;
        $steps = 8;

        $result = Bit::LogicalShiftRight($start, $steps);

        $this->assertEquals($expected, $result);
    }

    public function test_bitwise_or(){
        $expected = -1191;
        $start =  -1200;
        $compare =  73;

        $result = Bit::Or($start, $compare);

        $this->assertEquals($expected, $result);
    }

    public function test_bitwise_xor(){
        $expected = -1255;
        $start =  -1200;
        $compare =  73;

        $result = Bit::Xor($start, $compare);

        $this->assertEquals($expected, $result);
    }

    public function test_bitwise_and(){
        $expected = 64;
        $start =  -1200;
        $compare =  73;

        $result = Bit::And($start, $compare);

        $this->assertEquals($expected, $result);
    }

    public function test_bitwise_not(){
        $expected = 1199;
        $start =  -1200;

        $result = Bit::Not($start);

        $this->assertEquals($expected, $result);
    }
}
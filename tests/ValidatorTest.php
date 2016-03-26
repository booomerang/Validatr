<?php

use Validatr\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function falseRequiredValuesProvider()
    {
        return [
            'empty string' => [''],
            'null value' => [null],
            'empty string with 3 tabs' => ['   '],
        ];
    }

    /**
     * @dataProvider falseRequiredValuesProvider
     * @param $input
     */
    public function testValidateRequired($input)
    {
        $v = new Validator();
        $this->assertFalse($v->validateRequired($input));
    }

    public function testValidateMin()
    {
        $trueData = [
            ['fdfsfsf', 3],
            ['2dsh4353', 3],
            ['5', 3],
            [5, 3],
            ['1b245', 3],
            ['21b', 2],
            ['7.5', 2.3],
            ['2.4', '2.4']
        ];

        $falseData = [
            ['fdfsfsf', 7.5],
            ['2dsh4353', '32.41'],
            ['5', '5.01'],
            [4, 8],
            ['', 1],
            ['21b', '12'],
            ['2.3', 2.4]
        ];

        $v = new Validator();

        foreach($trueData as $row) {
            $this->assertTrue($v->validateMin($row[0], $row[1]));
        }

        foreach($falseData as $row) {
            $this->assertFalse($v->validateMin($row[0], $row[1]));
        }
    }

    public function testValidateMax()
    {
        $trueData = [
            ['fdfsfsf', 10],
            ['2dsh4353', 9.5],
            ['5', 7],
            [5, 6],
            ['', 1],
            ['1b245', 6],
            ['21b', 7],
            ['7.5', 7.55],
            ['2.4', '2.5'],
            [2.4, '2.5']
        ];

        $falseData = [
            [4, 2],
            ['fdfsfsf', 3.2],
            ['2dsh4353', '3.41'],
            ['5.01', '5.00'],
            ['vv', 1],
            ['21b', '2'],
            ['2.3', 2.1],
            [2.12, 1.89],
            [1.999, '1.99']
        ];

        $v = new Validator();

        foreach($trueData as $row) {
            $this->assertTrue($v->validateMax($row[0], $row[1]));
        }

        foreach($falseData as $row) {
            $this->assertFalse($v->validateMax($row[0], $row[1]));
        }
    }
}
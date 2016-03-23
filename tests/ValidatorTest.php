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
}
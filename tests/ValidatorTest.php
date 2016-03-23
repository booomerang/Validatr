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
    public function testIfIsRequired($input)
    {
        $v = new Validator();
        $this->assertFalse($v->isRequired($input));
    }

    public function testIfIsMin()
    {
        $v = new Validator();
        $this->assertTrue($v->isMin('dsh4353', 3));
    }
}
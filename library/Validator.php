<?php

namespace Validatr;

function pred($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die;
}

function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

class Validator
{
    private $currentFieldName;
    private $currentFieldValue;
    private $messages;
    private $rulesArray;
    private $specialRulesArray;
    private $defaultMessages = array(
        'required' => 'This field is required',
        'minLength' => 'Minimally allowable :value characters',
        'maxLength' => 'Maximum allowable :value characters',
        'email' => 'Invalid email address',
        'numeric' => 'Entered value of this field must be numeric',
        'boolean' => 'Entered value of this field must be boolean - 1 or 0',
        'alpha' => 'Allow only letters',
        'alnum' => 'Allow only letters and digits'
    );
    public $errors = array();

    public function validate(array $data, array $rules, $messages = array())
    {
        $this->messages = $messages;

        foreach($rules as $rulesListForField)
        {
            $rulesListForField = trim($rulesListForField);
            $rulesArray = explode('|', $rulesListForField);
            pre($rulesArray);
            pred($data);
            foreach($data as $key => $value)
            {

            }
        }
    }
}
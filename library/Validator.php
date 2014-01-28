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

    // TODO: Сделать фичу, для более жесткого сравнения значений с правилами, там где возможно.

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

        foreach($rules as $ruleKey => $rulesListForField)
        {
            if (!array_key_exists($ruleKey, $data)) {
                continue;
            }

            $rulesListForField = trim($rulesListForField);
            $rulesArray = explode('|', $rulesListForField);
            pre($rulesArray);
            pred($data);
            foreach($data as $key => $value)
            {

            }
        }
    }

    public function requredRule($dataValue)
    {
        if ($dataValue == '') {
            return false;
        }
        else {
            return true;
        }
    }

    public function minRule($ruleValue, $dataValue)
    {
        $ruleValueLen = (int) $ruleValue;
        $dataValue = mb_strlen($dataValue, 'UTF-8');
        $dataValueLen = (int) $dataValue;
        if ($dataValueLen < $ruleValueLen) {
            return false;
        } else {
            return true;
        }
    }

    public function maxRule($ruleValue, $dataValue)
    {
        $ruleValueLen = (int) $ruleValue;
        $dataValue = mb_strlen($dataValue, 'UTF-8');
        $dataValueLen = (int) $dataValue;
        if ($dataValueLen > $ruleValueLen) {
            return false;
        } else {
            return true;
        }
    }

    public function emailRule($dataValue)
    {
        if (filter_var($dataValue, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function numericRule($dataValue)
    {
        if (is_numeric($dataValue)) {
            return true;
        } else {
            return false;
        }
    }

    public function boolRule($dataValue)
    {
        if (is_bool($dataValue)) {
            return true;
        } else {
            return false;
        }
    }

    public function alphaRule($dataValue)
    {
        preg_match('/^[[:alpha:]]+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function alnumRule($dataValue)
    {
        preg_match('/^[[:alnum:]]+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if value contains alpha, numeric and some else characters
     * @param $ruleValue
     * @param $dataValue
     * @return bool
     */
    public function alnumRuleWith($ruleValue, $dataValue)
    {
        preg_match('/^[[:alnum:]'.$ruleValue.']+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function inRule($ruleValue, $dataValue)
    {
        $valuesArray = explode(',', $ruleValue);
        if (array_key_exists($dataValue, $valuesArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function notInRule($ruleValue, $dataValue)
    {
        $valuesArray = explode(',', $ruleValue);
        if (!array_key_exists($dataValue, $valuesArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function equalRule($ruleValue, $dataValue)
    {
        if ($ruleValue == $dataValue) {
            return true;
        } else {
            return false;
        }
    }

    public function notEqualRule($ruleValue, $dataValue)
    {
        if ($ruleValue != $dataValue) {
            return true;
        } else {
            return false;
        }
    }
}
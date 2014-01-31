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

    private $_messages;
    private $rulesArray;
    private $specialRulesArray;
    public $messages = array();
    private $defaultMessages = array(
        'required' => 'This field is required',
        'min' => 'Minimally allowable :value characters',
        'max' => 'Maximum allowable :value characters',
        'email' => 'Invalid email address',
        'numeric' => 'Entered value of this field must be numeric',
        'bool' => 'Entered value of this field must be boolean - 1 or 0',
        'alpha' => 'Allow only letters',
        'alnum' => 'Allow only letters and digits',
        'alnumWith' => 'Allow only letters and digits',
        'in' => 'Entered value must be in this list of words',
        'notIn' => 'Entered value must not be in this list of words',
        'equal' => 'Entered value must be equal :value',
        'notEqual' => 'Entered value must not be equal :value'
    );
    public $errors = array();

    public function validate(array $data, array $rules, $messages = array())
    {
        $this->_messages = $messages;

        foreach($rules as $ruleKey => $rulesListForField)
        {
            if (!array_key_exists($ruleKey, $data)) {
                continue;
            }

            $dataValue = $data[$ruleKey];
            $dataKey = $ruleKey;

            //pre($dataKey);

            $rulesListForField = trim($rulesListForField);
            $rulesArray = explode('|', $rulesListForField);
            //pre($rulesArray);

            foreach($rulesArray as $rule)
            {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleFunction = 'is'.$ruleParts[0];

                if (!isset($ruleParts[1])) {
                    $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleFunction);
                    $result = $reflectionMethod->invokeArgs($this, array($dataValue));
                } else {
                    $ruleFunctionParams = $ruleParts[1];

                    $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleFunction);
                    $result = $reflectionMethod->invokeArgs($this, array($dataValue, $ruleFunctionParams));
                }
                /*pre($ruleFunction);
                pre($ruleFunctionParams);*/

                /*if ($result) {
                    pre(1);
                } else {
                    pre(0);
                }*/

                $this->checkError($result, $dataKey, $ruleName);
            }
        }
        return !empty($this->messages) ? $this->messages : true;
    }

    private function checkError($result, $dataKey, $ruleName)
    {
        if (!$result) {
            if (isset($this->_messages[$dataKey][$ruleName])) {
                $this->messages[$dataKey][$ruleName] = $this->_messages[$dataKey][$ruleName];
            } else {
                $this->messages[$dataKey][$ruleName] = $this->defaultMessages[$ruleName];
            }
        }
    }

    public function isRequired($dataValue)
    {
        if ($dataValue == '') {
            return false;
        }
        else {
            return true;
        }
    }

    public function isMin($dataValue, $ruleValue)
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

    public function isMax($dataValue, $ruleValue)
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

    public function isEmail($dataValue)
    {
        if (filter_var($dataValue, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function isNumeric($dataValue)
    {
        if (is_numeric($dataValue)) {
            return true;
        } else {
            return false;
        }
    }

    public function isBool($dataValue)
    {
        if ($dataValue == '0' || $dataValue == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function isAlpha($dataValue)
    {
        preg_match('/^[[:alpha:]]+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function isAlnum($dataValue)
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
     * @param string $ruleValue
     * @param string $dataValue
     * @return bool
     */
    public function isAlnumWith($dataValue, $ruleValue)
    {
        preg_match('/^[[:alnum:]'.$ruleValue.']+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /** Checks if value is included in the given list of values.
     * @param string $dataValue Field value
     * @param string $ruleValue List of values with comma as delimiter
     * @return bool
     */
    public function isIn($dataValue, $ruleValue)
    {
        $valuesArray = explode(',', $ruleValue);
        foreach($valuesArray as $value)
        {
            if ($value == $dataValue) {
                return true;
            }
        }
        return false;
    }

    public function isNotIn($dataValue, $ruleValue)
    {
        $valuesArray = explode(',', $ruleValue);
        foreach($valuesArray as $value)
        {
            if ($value == $dataValue) {
                return false;
            }
        }
        return true;
    }

    public function isEqual($dataValue, $ruleValue)
    {
        if ($ruleValue == $dataValue) {
            return true;
        } else {
            return false;
        }
    }

    public function isNotEqual($dataValue, $ruleValue)
    {
        if ($ruleValue != $dataValue) {
            return true;
        } else {
            return false;
        }
    }
}
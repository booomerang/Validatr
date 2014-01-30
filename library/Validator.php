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
        'boolean' => 'Entered value of this field must be boolean - 1 or 0',
        'alpha' => 'Allow only letters',
        'alnum' => 'Allow only letters and digits'
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
                $ruleFunction = $ruleParts[0].'Rule';

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

    public function requiredRule($dataValue)
    {
        if ($dataValue == '') {
            return false;
        }
        else {
            return true;
        }
    }

    public function minRule($dataValue, $ruleValue)
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

    public function maxRule($dataValue, $ruleValue)
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
    public function alnumRuleWith($dataValue, $ruleValue)
    {
        preg_match('/^[[:alnum:]'.$ruleValue.']+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function inRule($dataValue, $ruleValue)
    {
        $valuesArray = explode(',', $ruleValue);
        if (array_key_exists($dataValue, $valuesArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function notInRule($dataValue, $ruleValue)
    {
        $valuesArray = explode(',', $ruleValue);
        if (!array_key_exists($dataValue, $valuesArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function equalRule($dataValue, $ruleValue)
    {
        if ($ruleValue == $dataValue) {
            return true;
        } else {
            return false;
        }
    }

    public function notEqualRule($dataValue, $ruleValue)
    {
        if ($ruleValue != $dataValue) {
            return true;
        } else {
            return false;
        }
    }
}
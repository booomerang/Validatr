<?php

namespace Validatr;

class Validator
{
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

            $dataKey = $ruleKey; // Field's Name
            $dataValue = $data[$ruleKey]; // Field's Value

            $rulesListForField = trim($rulesListForField);
            $rulesArray = explode('|', $rulesListForField);

            foreach($rulesArray as $rule)
            {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleFunction = 'is'.$ruleName;

                if (!isset($ruleParts[1])) {
                    $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleFunction);
                    $result = $reflectionMethod->invokeArgs($this, array($dataValue));
                } else {
                    $ruleFunctionParams = $ruleParts[1];

                    // for strict mode comparison
                    if (isset($ruleParts[2]) && (strtolower($ruleParts[2]) == '[strict]' || strtolower($ruleParts[2]) == '[s]')) {
                        $strict = true;

                        $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleFunction);
                        $result = $reflectionMethod->invokeArgs($this, array($dataValue, $ruleFunctionParams, $strict));
                    } else {
                        $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleFunction);
                        $result = $reflectionMethod->invokeArgs($this, array($dataValue, $ruleFunctionParams));
                    }
                }

                $this->checkError($result, $dataKey, $ruleName);
            }
        }
        return !empty($this->messages) ? $this->messages : true;
    }

    /**
     * Method for setting messages in array
     * @param bool $result Result of rule function
     * @param string $dataKey Name of field
     * @param string $ruleName Name of rule function
     */
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
        $dataValue = mb_strlen($dataValue, 'UTF-8');
        if ((int) $dataValue < (int) $ruleValue) {
            return false;
        } else {
            return true;
        }
    }

    public function isMax($dataValue, $ruleValue)
    {
        $dataValue = mb_strlen($dataValue, 'UTF-8');
        if ((int) $dataValue > (int) $ruleValue) {
            return false;
        } else {
            return true;
        }
    }

    public function isEmail($dataValue)
    {
        return filter_var($dataValue, FILTER_VALIDATE_EMAIL);
    }

    public function isNumeric($dataValue)
    {
        return is_numeric($dataValue);
    }

    /**
     * Method returns TRUE for values "1", "true", "on" and "yes". Otherwise returns FALSE.
     * @param mixed $dataValue Value for validating
     * @return bool
     */
    public function isBool($dataValue)
    {
        return filter_var($dataValue, FILTER_VALIDATE_BOOLEAN);
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
     * @param string $dataValue
     * @param string $ruleValue
     * @return bool
     */
    public function isAlnumWith($dataValue, $ruleValue)
    {
        preg_match('/^[[:alnum:]'.preg_quote($ruleValue, '/').']+$/iu', $dataValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if value is included in the given list of values.
     * @param mixed $dataValue Field value
     * @param mixed $ruleValue List of values with comma as delimiter
     * @param bool $strict Mode for strict comparison
     * @return bool
     */
    public function isIn($dataValue, $ruleValue, $strict = false)
    {
        $valuesArray = explode(',', $ruleValue);
        $valuesArray = array_map('trim', $valuesArray);

        if ($strict == false) {
            foreach($valuesArray as $value)
            {
                $dataValue = mb_strtolower($dataValue, 'UTF-8');
                $value = mb_strtolower($value, 'UTF-8');

                if ($value == $dataValue) {
                    return true;
                }
            }
        } else {
            return in_array($dataValue, $valuesArray);
        }
        return false;
    }

    /**
     * Checks if value is not included in the given list of values.
     * @param mixed $dataValue Field value
     * @param mixed $ruleValue List of values with comma as delimiter
     * @param bool $strict Mode for strict comparison
     * @return bool
     */
    public function isNotIn($dataValue, $ruleValue, $strict = false)
    {
        $valuesArray = explode(',', $ruleValue);
        $valuesArray = array_map('trim', $valuesArray);

        if ($strict == false) {
            foreach($valuesArray as $value)
            {
                $dataValue = mb_strtolower($dataValue, 'UTF-8');
                $value = mb_strtolower($value, 'UTF-8');

                if ($value == $dataValue) {
                    return false;
                }
            }
        } else {
            return !in_array($dataValue, $valuesArray);
        }
        return true;
    }

    public function isEqual($dataValue, $ruleValue, $strict = false)
    {
        if ($strict == false) {
            $dataValue = mb_strtolower($dataValue, 'UTF-8');
            $ruleValue = mb_strtolower($ruleValue, 'UTF-8');
        }
        if ($dataValue == $ruleValue) {
            return true;
        } else {
            return false;
        }
    }

    public function isNotEqual($dataValue, $ruleValue, $strict = false)
    {
        if ($strict == false) {
            $dataValue = mb_strtolower($dataValue, 'UTF-8');
            $ruleValue = mb_strtolower($ruleValue, 'UTF-8');
        }
        if ($dataValue != $ruleValue) {
            return true;
        } else {
            return false;
        }
    }
}
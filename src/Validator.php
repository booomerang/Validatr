<?php

namespace Validatr;

class Validator
{
    private $_messages;
    private $rulesArray;
    private $customRuleFunctionsArray;
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

    /**
     * @param array $data Any data in format key => value
     * @param array $rules Special array with your rules and values
     * Each rule (with value) must be divided by "|".
     * Each rule and value must be divided by ":".
     * Additional parameters for rules must be divided by ":".
     * @param array $messages Your error messages on your language for values, which is not valid
     * @return array|bool An array with error messages or true if all data is valid
     * @throws \Exception
     * @example
     * array (
     *    'name' => 'ok'
     *    'password' => 'ok'
     *    'email' => 'ok@gmail.com'
     *    'checkbox' => '1'
     * );
     * @example
     * array (
     *    'name' => 'required|min:3|max:15|equal:Boss:[s]',
     *    'password' => 'required|min:3',
     *    'email' => 'required|email',
     *    'checkbox' => 'bool'
     * );
     * @example
     * array (
     *     'name' => array (
     *         'min' => 'Minimum required 3 characters',
     *         'equal' => 'Значение должно равняться - Boss'
     *     ),
     *     'password' => array (
     *         'required' => 'Поле обязательно для заполнения',
     *         'min' => 'Minimalement acceptables 3 caractères'
     *     )
     * );
     */
    public function validate(array $data, array $rules, $messages = array())
    {
        $this->_messages = $messages;

        foreach($rules as $ruleKey => $rulesListForField)
        {
            // Skip, if not field for validation
            if (!array_key_exists($ruleKey, $data)) {
                continue;
            }

            $dataKey = $ruleKey; // Field Name
            $dataValue = $data[$ruleKey]; // Field Value

            $rulesListForField = trim($rulesListForField);
            $rulesArray = explode('|', $rulesListForField);

            foreach($rulesArray as $rule)
            {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleMethod = 'validate'.$ruleName;

                if (method_exists($this, $ruleMethod)) {
                    if (!isset($ruleParts[1])) {
                        $result = $this->$ruleMethod($dataValue);
                    } else {
                        $ruleMethodParams = $ruleParts[1];

                        // for strict mode comparison
                        if (isset($ruleParts[2]) && (strtolower($ruleParts[2]) == '[strict]' || strtolower($ruleParts[2]) == '[s]')) {
                            $result = $this->$ruleMethod($dataValue, $ruleMethodParams, true);
                        } else {
                            $result = $this->$ruleMethod($dataValue, $ruleMethodParams);
                        }
                    }
                } elseif ( // For custom rule functions added with addRule() method
                    isset($this->customRuleFunctionsArray[$ruleName]) && is_callable($this->customRuleFunctionsArray[$ruleName])
                ) {
                    $count = count($ruleParts);
                    if ($count == 1) {
                        $result = $this->customRuleFunctionsArray[$ruleName]($dataValue);
                    } elseif ($count == 2) {
                        $ruleMethodParams = $ruleParts[1];
                        $result = $this->customRuleFunctionsArray[$ruleName]($dataValue, $ruleMethodParams);
                    } else {
                        $ruleMethodParams = $ruleParts[1];
                        // Creating an array with rule parameters, which more than 2
                        $params = array_slice($ruleParts, 2);
                        $result = $this->customRuleFunctionsArray[$ruleName]($dataValue, $ruleMethodParams, $params);
                    }
                } else {
                    throw new \Exception("Rule method \"{$ruleName}\" doesn't exist.");
                }

                /*if (!isset($ruleParts[1])) {
                    $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleMethod);
                    $result = $reflectionMethod->invokeArgs($this, array($dataValue));
                } else {
                    $ruleFunctionParams = $ruleParts[1];

                    // for strict mode comparison
                    if (isset($ruleParts[2]) && (strtolower($ruleParts[2]) == '[strict]' || strtolower($ruleParts[2]) == '[s]')) {
                        $strict = true;

                        $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleMethod);
                        $result = $reflectionMethod->invokeArgs($this, array($dataValue, $ruleFunctionParams, $strict));
                    } else {
                        $reflectionMethod = new \ReflectionMethod(__CLASS__, $ruleMethod);
                        $result = $reflectionMethod->invokeArgs($this, array($dataValue, $ruleFunctionParams));
                    }
                }*/

                $this->checkError($result, $dataKey, $ruleName);
            }
        }
        return !empty($this->messages) ? $this->messages : true;
    }

    /**
     * Method for setting messages in array
     * @param bool $result Result of rule function - true or false
     * @param string $dataKey Name of field
     * @param string $ruleName Name of rule function
     * @throws \Exception
     */
    private function checkError($result, $dataKey, $ruleName)
    {
        if (!$result) {
            if (isset($this->_messages[$dataKey][$ruleName])) {
                $this->messages[$dataKey][$ruleName] = $this->_messages[$dataKey][$ruleName];
            } elseif (isset($this->defaultMessages[$ruleName])) {
                $this->messages[$dataKey][$ruleName] = $this->defaultMessages[$ruleName];
            } else {
                throw new \Exception("Message for custom rule method \"{$ruleName}\" doesn't set");
            }
        }
    }

    public function addRule($ruleName, \Closure $callbackFunction)
    {
        $ruleName = (string) $ruleName;
        $this->customRuleFunctionsArray[$ruleName] = $callbackFunction;
    }

    public function validateRequired($input)
    {
        if (is_null($input)) {
            return false;
        } elseif (is_string($input) && trim($input) === '') {
            return false;
        } elseif ((is_array($input) || $input instanceof \Countable) && count($input) < 1) {
            return false;
        }

        return true;
    }

    public function validateMin($input, $sizeMin)
    {
        $inputSize = $this->getSize($input);
        return (is_numeric($sizeMin)) && ($inputSize >= $sizeMin);
    }

    public function validateMax($input, $sizeMax)
    {
        $inputSize = $this->getSize($input);
        return (is_numeric($sizeMax)) && ($inputSize <= $sizeMax);
    }

    /**
     * Get size of a given input value
     * @param $input
     * @return int
     */
    protected function getSize($input)
    {
        if (is_numeric($input)) {
            return $input;
        } elseif ((is_array($input) || $input instanceof \Countable)) {
            return count($input);
        }
        return mb_strlen($input, 'UTF-8');
    }

    /**
     * Validate email input
     * @param $input
     * @return bool
     */
    public function validateEmail($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) === false ? false: true;
    }

    public function validateNumeric($input)
    {
        return is_numeric($input);
    }

    /**
     * Method returns TRUE for values "1", "true", "on" and "yes". Otherwise returns FALSE.
     * @param $input
     * @return bool
     */
    public function validateBool($input)
    {
        return filter_var($input, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * The input value under validation must be entirely alphabetic characters.
     * @param $input
     * @return bool
     */
    public function validateAlpha($input)
    {
        preg_match('/^[[:alpha:]]+$/iu', $input, $result);
        return (is_string($input) && !empty($result)) ? true : false;
    }

    public function validateAlnum($input)
    {
        if (! is_string($input) && ! is_numeric($input)) {
            return false;
        }
        preg_match('/^[[:alnum:]]+$/iu', $input, $result);
        return !empty($result) ? true : false;
    }

    /**
     * Checks if value contains alpha, numeric and some else custom characters
     * @param $input
     * @param string $additionalSymbolsList
     * @return bool
     */
    public function validateAlnumWith($input, $additionalSymbolsList)
    {
        preg_match('/^[[:alnum:]'.preg_quote($additionalSymbolsList, '/').']+$/iu', $input, $result);
        return !empty($result) ? true : false;
    }

    /**
     * Checks if value is included in the given list of values.
     * @param mixed $input Field value
     * @param string $valuesList List of values with comma as delimiter
     * @param bool $strict Mode for strict comparison
     * @return bool
     */
    public function validateIn($input, $valuesList, $strict = false)
    {
        $valuesArray = explode(',', $valuesList);
        $valuesArray = array_map('trim', $valuesArray);

        if ($strict == false) {
            foreach($valuesArray as $value)
            {
                $dataValue = mb_strtolower($input, 'UTF-8');
                $value = mb_strtolower($value, 'UTF-8');

                if ($value == $dataValue) {
                    return true;
                }
            }
        } else {
            return in_array($input, $valuesArray);
        }
        return false;
    }

    /**
     * Checks if value is not included in the given list of values.
     * @param mixed $input Field value
     * @param string $valuesList List of values with comma as delimiter
     * @param bool $strict Mode for strict comparison
     * @return bool
     */
    public function validateNotIn($input, $valuesList, $strict = false)
    {
        return ! $this->validateIn($input, $valuesList, $strict);
    }

    public function validateEqual($input, $comparingValue, $strict = false)
    {
        if ($strict == false) {
            $input = mb_strtolower($input, 'UTF-8');
            $comparingValue = mb_strtolower($comparingValue, 'UTF-8');
        }
        return ($input === $comparingValue) ? true : false;
    }

    public function validateNotEqual($input, $comparingValue, $strict = false)
    {
        return ! $this->validateEqual($input, $comparingValue, $strict);
    }
}
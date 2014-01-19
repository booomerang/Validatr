<?php

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
        'email' => 'Invalid email address - :value',
        'numeric' => 'Entered value of this field must be numeric',
        'boolean' => 'Entered value of this field must be booleadn - 1 or 0',
        'alpha' => 'Allow only letters',
        'alnum' => 'Allow only letters and digits'
    );
    public $errors = array();

    public function validate(array $data, array $rules, $messages = array())
    {
        $this->messages = $messages;

        foreach($rules as $fieldName => $rulesForField)
        {
            // Skip, if not field for validation
            if (!array_key_exists($fieldName, $data)) {
                continue;
            }

            $this->currentFieldName = $fieldName;
            $this->currentFieldValue = $data[$fieldName];
            $this->rulesArray = $rulesForField;

            foreach($rulesForField as $ruleKey => $ruleValue)
            {
                if (isset($this->specialRulesArray[$ruleKey]) && is_callable($this->specialRulesArray[$ruleKey])) {
                    $result = $this->specialRulesArray[$ruleKey]($ruleValue, $this->currentFieldValue);
                } else {
                    $ruleFunction = $ruleKey.'Rule';
                    $reflectionMethod = new ReflectionMethod(__CLASS__, $ruleFunction);
                    $result = $reflectionMethod->invokeArgs($this, array($ruleValue));
                }

                $this->checkError($result, $ruleKey, $ruleValue);
            }

            unset($this->messages[$this->currentFieldName]);
            if (!empty($this->rulesArray)) {
                $this->errors[$this->currentFieldName] = $this->rulesArray;
            }
        }
        return !empty($this->errors) ? $this->errors : true;
    }

    protected function checkError($result, $ruleKey, $ruleValue)
    {
        if (!$result) {
            if (isset($this->messages[$this->currentFieldName][$ruleKey])) {
                $this->rulesArray[$ruleKey] = $this->handleErrorMessageValue($this->messages[$this->currentFieldName][$ruleKey], $ruleValue);
            } elseif (isset($this->defaultMessages[$ruleKey])) {
                $this->rulesArray[$ruleKey] = $this->handleErrorMessageValue($this->defaultMessages[$ruleKey], $ruleValue);
            }
        } else {
            unset($this->rulesArray[$ruleKey]);
        }
    }

    private function handleErrorMessageValue($message, $ruleValue)
    {
        $message = preg_replace('/(?<!\!):value/i', $ruleValue, $message);
        $message = preg_replace('~!:value~i', ':value', $message);
        return $message;
    }

    public function addRule($ruleName, \Closure $function)
    {
        $this->specialRulesArray[$ruleName] = $function;
    }

    public function requiredRule()
    {
        if ($this->currentFieldValue == '') {
            return false;
        } else {
            return true;
        }
    }

    public function minLengthRule($ruleValue)
    {
        $ruleValue = (int) $ruleValue;
        $fieldValue = mb_strlen($this->currentFieldValue, 'UTF-8');
        $fieldValue = (int) $fieldValue;
        if ($fieldValue < $ruleValue) {
            return false;
        } else {
            return true;
        }
    }

    public function maxLengthRule($ruleValue)
    {
        $ruleValue = (int) $ruleValue;
        $fieldValue = mb_strlen($this->currentFieldValue, 'UTF-8');
        $fieldValue = (int) $fieldValue;
        if ($fieldValue > $ruleValue) {
            return false;
        } else {
            return true;
        }
    }

    public function emailRule()
    {
        if (filter_var($this->currentFieldValue, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function numericRule()
    {
        if (is_numeric($this->currentFieldValue)) {
            return true;
        } else {
            return false;
        }
    }

    public function booleanRule()
    {
        if (is_bool($this->currentFieldValue)) {
            return true;
        } else {
            return false;
        }
    }

    public function alphaRule()
    {
        preg_match('/^[[:alpha:]]+$/iu', $this->currentFieldValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function alnumRule()
    {
        preg_match('/^[[:alnum:]]+$/iu', $this->currentFieldValue, $result);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }
}

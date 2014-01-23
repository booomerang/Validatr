<?php

// Set rules for some fields in form
$rules = array(
    'name' => array(
        'required'  => true,
        'minLength' => 4,
        'maxLength' => 8,
        'alnum'     => true,
        'equal'     => 'okOk' // Custom rule, defined below
    ),
    'password' => array(
        'required'  => true,
        'minLength' => 4,
        'maxLength' => 20,
        'alpha'     => true,
        'boolean'   => true,
        'equal'  => 'Paroli' // We can use our custom rule on all fields
    ),
    'email' => array (
        'required' => true,
        'email'    => true
    )
);

// Your error messages on your language
$messages = array(
    'name' => array(
        'required' => 'Поле обязательно для заполнения',
        'minLength' => 'Минимально !:value []%#^$&@#~!@#$%^&*( допустимоооооо :value символов',
        'equal' => 'Not equils for :value',
        'maxLength' => 'Максимально допустимо :value символов'
    ),
    'password' => array(
        'required' => 'Поле обязательно для заполнения',
        'maxLength' => 'Максимально допустимо :value символов',
        'equal' => 'Пароль должен равняться этому значению - :value'
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

// Custom rule Function for your needs
$validator->addRule('equal', function($ruleValue, $fieldValue) {
    return ($ruleValue == $fieldValue) ? true : false;
});

$result = $validator->validate($_POST, $rules, $messages);

//var_dump($result);

echo "<pre>";
print_r($result);
echo "</pre>";
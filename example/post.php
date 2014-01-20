<?php

$rules = array(
    'name' => array(
        'required'  => true,
        'minLength' => 4,
        'maxLength' => 8,
        'equal'  => 'okOk' // Custom rule, defined below
        /*'email'     => true,
        'boolean'     => true,
        'numeric'   => true,
        'alpha'   => true,
        'alnum'   => true*/
    ),
    'password' => array(
        'required'  => true,
        'minLength' => 4,
        'maxLength' => 20,
        'alpha'     => true
    )
);

// Your error messages on your language
$messages = array(
    'name' => array(
        'required' => 'Поле обязательно для заполнения',
        'minLength' => 'Минимально !:value []%#^$&@#~!@#$%^&*( допустимоооооо :value символов',
        'equal' => 'PHPVALIDATOR!!!',
        'maxLength' => 'Максимально допустимо :value символов',
    ),
    'password' => array(
        'required' => 'Поле обязательно для заполнения',
        'minLength' => 'ок!',
        'maxLength' => 'Максимально допустимо :value символов',
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

$validator->addRule('equal', function($ruleValue, $fieldValue) {
    return ($ruleValue == $fieldValue) ? true : false;
});

$result = $validator->validate($_POST, $rules, $messages);

var_dump($result);
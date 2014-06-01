<?php

ini_set('display_errors', 1);

// Set rules for some fields in form
$rules = array (
    'name' => 'required|min:3|max:15|equal:Boss:[s]',
    'password' => 'required|min:3|custom_equal:OK!',
    'email' => 'required|email',
    'checkbox' => 'bool'
);

// Your error messages on your language
$messages = array (
    'name' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Minimum required 3 characters',
        'max' => 'Maximal zulässige fünfzehn Zeichen',
        'equal' => 'Значение должно равняться - Boss'
    ),
    'password' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Minimalement acceptables 3 caractères',
        'custom_equal' => 'Кастомное сообщение для правила equals'
    ),
    'checkbox' => array (
        'bool' => 'Вы не выставили галочку!'
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

$validator->addRule('custom_equal', function($dataValue, $ruleValue){
    return ($dataValue == $ruleValue) ? true : false;
});

$result = $validator->validate($_POST, $rules, $messages);

echo "<pre>";
print_r($result);
echo "</pre>";
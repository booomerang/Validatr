<?php

// Set rules for some fields in form
$rules = array (
    'name' => 'required|min:3|max:15|equal:Boss:[s]',
    'password' => 'required|min:3',
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
        'min' => 'Minimalement acceptables 3 caractères'
    ),
    'checkbox' => array (
        'bool' => 'Вы не выставили галочку!'
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

$result = $validator->validate($_POST, $rules, $messages);

echo "<pre>";
print_r($result);
echo "</pre>";
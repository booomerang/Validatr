<?php
/*
$rules =
'
required
min
max
email
bool
numeric
alpha
alnum
alnumWith
in
notIn
equal
notEqual
confirm?
exists?(DB)
unique?(DB)
';


$rules = array (
    'name' => 'required|minLenght:4|maxLenght:5|alnum|equal:Petea',
    'password' => 'required|minLenght:3',
    'email' => 'required|email',
);

$messages = array (
    'name.required'  => 'Поле обязательно для заполнения',
    'name.minLength' => 'Минимально !:value []%#^$&@#~!@#$%^&*( допустимоооооо :value символов',
    'name.maxLength' => 'Not equils for :value',
    'name.equal'     => 'Максимально допустимо :value символов',

    'email.required' => 'Максимально допустимо :value символов',
    'email.email'    => 'Максимально допустимо :value символов'
);

$rules = array (
    'name' => 'required|minLenght[4,8]|maxLenght:6,8|alnum|equal(Petea,Vasea)',
    'email' => 'required|email',
);

$messages = array(
    'name' => array(
        'required' => 'Поле обязательно для заполнения',
        'minLength' => 'Минимально !:value []%#^$&@#~!@#$%^&*( допустимоооооо :value символов',
        'equal' => 'Not equils for :value',
        'maxLength' => 'Максимально допустимо :value символов'
    ),
    'email' => array(
        'required' => 'Поле обязательно для заполнения',
        'email' => 'Максимально допустимо :value символов'
    )
);

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
        'maxLength' => 'Максимально допустимо :value символов',
        'equal' => 'Not equils for :value'
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
echo "</pre>";*/







/*

Array
(
    [name] => dadada
    [password] => paroli
    [email] => ok@gmail.com
)

 */




$rules = array (
    'name' => 'required|min:3|max:15|bool',
    'password' => 'required|min:3',
    'email' => 'required|email',
);

// Your error messages on your language
$messages = array (
    'name' => array (
        'required' => 'Поле обязательно для заполнения',
        'minLength' => 'Минимально !:value []%#^$&@#~!@#$%^&*( допустимоооооо :value символов',
        'maxLength' => 'Максимально допустимо :value символов',
        'equal' => 'Not equils for :value'
    ),
    'password' => array (
        'required' => 'Поле обязательно для заполнения',
        'maxLength' => 'Максимально допустимо :value символов',
        'equal' => 'Пароль должен равняться этому значению - :value'
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

$result = $validator->validate($_POST, $rules, $messages);

echo "<pre>";
print_r($result);
echo "</pre>";
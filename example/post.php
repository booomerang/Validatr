<html>
<head>
    <meta charset="utf-8">
</head>
<body>
</body>
</html>
<?php
/*

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
*required
*min
*max
*email
?bool
*numeric
*alpha
*alnum
?alnumWith - Special chars and ':' ?
?in - ':'?
?notIn - ':'?
?equal[s] - ':'?
?notEqual[s] - ':'?
confirm?
regexp?
exists?(DB)
unique?(DB)



$rules = array (
    'name' => 'required|min:4|max:15|email|bool|numeric',
    'password' => 'alpha|alnum|alnumWith:-+_|in:1,2,3|notIn:4,5|equal:Petea|notEqual:Vasea',
    'email' => 'required|email',
);
*/


/*

Array
(
    [name] => dadada
    [password] => paroli
    [email] => ok@gmail.com
)

 */

//var_dump($_POST);
//die;

ini_set('display_errors', 1);
error_reporting(E_ALL);


$rules = array (
    'name' => 'required|min:3|max:15|bool',
    'password' => 'required|min:3',
    'email' => 'required|email',
    'checkbox' => 'bool'
);

// Your error messages on your language
$messages = array (
    'name' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Минимально допустимо :value символов',
        'max' => 'Максимально допустимо :value символов'
    ),
    'password' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Максимально допустимо :value символов'
    )
);

require_once '../library/Validator.php';
$validator = new Validatr\Validator();

//$result = $validator->validate($_POST, $rules, $messages);

$boolCheckRes = $validator->isIn('окидоки', 'ok, 1 , окидоки');
/*$boolCheckRes2 = $validator->isBool('11');
$boolCheckRes3 = $validator->isAlnumWith('авава1/', "/");
$boolCheckRes4 = $validator->isIn('ok', 'Ok,2,3');*/

var_dump($boolCheckRes);
/*var_dump($boolCheckRes2);
var_dump($boolCheckRes3);
var_dump($boolCheckRes4);*/
echo '=======';
echo '<br>';
die;

/*$ok = 1;
$ok++;
$ok += 10;

echo "AAAAA";
echo $ok;
echo "CCCCC";*/
echo "<pre>";
print_r($result);
echo "</pre>";
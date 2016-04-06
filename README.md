#Validatr

[![Build Status](https://travis-ci.org/booomerang/Validatr.svg?branch=master)](https://travis-ci.org/booomerang/Validatr)

Validatr is a simple multilingual PHP Validation library for checking user's data.

  - Simple form for rules and errors messages
  - Flexible and extendable library with your callback rules
  - Enjoyment!

Validatr is a standalone PHP class, which can be extended for your needs.

## Table of contents

 - [Getting started](#getting-started)
 - [Installation](#installation)
 - [How to use](#how-to-use)
 - [Contributing](#contributing)
 - [Versioning](#versioning)
 - [Authors](#authors)
 - [Copyright and license](#copyright-and-license)

## Getting started

1. PHP >= 5.4 is required
2. Install Validatr convenient for you
3. Check your data with Validatrs rules
4. Enjoy!

## Installation

1) Via composer

```
{
    "require": {
        "boomerang/validatr": "~3.0"
    }
}
```

To download the library run the command:

```
$ php composer.phar require boomerang/validatr
```
or
```
$ composer require boomerang/validatr
```

2) Git clone

```
git clone git@github.com:booomerang/Validatr.git
```

3) Download

[Download .zip](https://github.com/booomerang/Validatr/archive/master.zip "Download .zip")

Unzip it and copy the directory into your PHP project directory.

## How to use

Simple Form

```html
<form action="post.php" method="POST">

    <input type="text" name="name" id="name" placeholder="Name" />
    <input type="password" name="password" id="password" placeholder="Name" />
    <input type="email" name="email" id="email" placeholder="Name" />

    <label for="checkbox">Checkbox</label>
    <input type="hidden" name="checkbox" value="0" />
    <input type="checkbox" name="checkbox" id="checkbox" value="1" /><br/>

    <input type="submit" />

</form>
```

Form Handler with Validatr class

```php
// Set rules for some fields in form
$rules = [
    'name' => 'required|min:3|max:15|equal:Awesome:[s]',
    'password' => 'required|min:3|custom_equal:OK', // Custom rule, defined below
    'email' => 'required|email',
    'checkbox' => 'bool'
];

// Your error messages on your language
$messages = array (
    'name' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Minimum required 3 characters',
        'max' => 'Maximal zulässige fünfzehn Zeichen',
        'equal' => 'Значение должно равняться - Awesome'
    ),
    'password' => array (
        'required' => 'Поле обязательно для заполнения',
        'min' => 'Minimalement acceptables 3 caractères',
        'custom_equal' => 'Кастомное сообщение для правила custom_equal'
    ),
    'checkbox' => array (
        'bool' => 'Вы не выставили галочку!'
    )
);

$validator = new \Validatr\Validator();

// Adding custom rule
$validator->addRule('custom_equal', function($dataValue, $ruleValue){
    return ($dataValue == $ruleValue) ? true : false;
});

$result = $validator->validate($_POST, $rules, $messages);

echo "<pre>";
print_r($result);
echo "</pre>";
```

### Set messages

#### //Todo

### Return Values

Method `validate()` returns one of two types:

AN ARRAY containing in keys names of form fields and in values nested associative array containing in keys validation rules and in values error messages. (See below example).

or

A BOOLEAN value of TRUE if the validation was successful.

**Return values example:**

The result may be (if all fields were sent empty):
```
Array
(
    [name] => Array
        (
            [required] => Поле обязательно для заполнения
            [min] => Minimum required 3 characters
            [equal] => Значение должно равняться - Boss
        )

    [password] => Array
        (
            [required] => Поле обязательно для заполнения
            [min] => Minimalement acceptables 3 caractères
        )

    [email] => Array
        (
            [required] => This field is required
            [email] => Invalid email address
        )

    [checkbox] => Array
        (
            [bool] => Вы не выставили галочку!
        )

)
```

Or if all fields was validated successfully:

```php
1
```

### Available rules:

```
- required  // Checks if field's value is not empty
- min       // Checks if the number of characters of field's value not less than rule value (UTF-8)
- max       // Checks if the number of characters of field's value not greater than rule value (UTF-8)
- email     // Checks if field's value is a valid email adress
- numeric   // Checks if field contains only numeric value
- bool      // Checks if field's value is boolean
- alpha     // Checks if field's value contains only alphabetic characters (UTF-8)
- alnum     // Checks if field's value contains only alphabetic and numeric characters (UTF-8)
- alnumWith // Checks if field's value contains only alphabetic and numeric characters (UTF-8) and some else custom characters
- in        // Checks if value is included in the given list of values.
- notIn     // Checks if value is not included in the given list of values.
- equal     // checks if value is equal to rule value (Strict or not)
- notEqual  // checks if value is bot equal to rule value (Strict or not)
```

### Creating your own validating rules

For creating your own validating rules use addRule method.

```php
// Lets create our own 'equal' rule.

$data = array (
    'name' => 'ok'
);

$rules = array(
    'name' => 'custom_equal:OK:strict:upper'
);

$messages = array(
    'name' => array(
        'custom_equal' => 'The value must be equal "ok"'
    )
);

// The callback function may receives three arguments:
// 1st arg - field value - 'ok' (From $data)
// 2nd arg - rule value - 'OK' (From $rules 2nd param)
// 3rd arg - additional params - array (From $rules starting 3nd and more params)

// $params in this example is an array - array('strict', 'upper'); But not used
// It should return a boolean value indicating whether the value is valid.
$validator->addRule('custom_equal', function($dataValue, $ruleValue, $params){
    return ($dataValue == $ruleValue) ? true : false;
});

$result = $validator->validate($data, $rules, $messages);
```

## Contributing

1. Fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Make your changes
4. Write the unit tests for your new feature (phpunit)
5. Commit your changes (git commit -am 'Add some feature')
6. Push to the branch (git push origin my-new-feature)
7. Create new Pull Request


## Versioning

Validatr is maintained under the Semantic Versioning guidelines. Sometimes we screw up, but we'll adhere to these rules whenever possible.

Releases will be numbered with the following format:

```
<major>.<minor>.<patch>
```

And constructed with the following guidelines:

- Breaking backward compatibility bumps the major while resetting minor and patch
- New additions without breaking backward compatibility bumps the minor while resetting the patch
- Bug fixes and misc changes bumps only the patch

For more information on SemVer, please visit http://semver.org/.

Brazenly copied from [Bootstrap's README.md](https://github.com/twbs/bootstrap#versioning "Bootstrap versioning") =)

## Authors

Alex D.

-----

Inspired by:

- http://jqueryvalidation.org/
- https://github.com/selahattinunlu/phpValidator
- https://github.com/Wixel/GUMP
- http://laravel.com/docs/validation
- https://github.com/Respect/Validation

## Copyright and License

Copyright 2016 Alex D, licensed under the [MIT](http://opensource.org/licenses/MIT "Bootstrap versioning").
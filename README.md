#Validatr

Validatr is a simple multilingual Php Validator library for checking user's data.

  - Simple form for rules and errors messages
  - Flexible and extendable with your callbacks rules functions
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

1. Php >=5.3.0 is required
2. Install Validatr convenient for you
3. Check your data with Validatrs rules
4. Enjoy!

## Installation

1) Via composer

```
{
    "require": {
        "boomerang/validatr": "dev-master"
    }
}
```

To download the library run the command:

```
$ php composer.phar update boomerang/validatr
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
<form action="post.php" method="post">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" />
    <br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
    <br>
    <label for="email">Email</label>
    <input type="text" name="email" id="email" />

    <br>
    <input type="submit" />
</form>
```

Form Handler with Validatr class

```php
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
        'maxLength' => 'Максимально допустимо :value символов',
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
```

### Set messages
You can set the error's messages on your language, which will be shown for non-valid data.

    Notice!
        You can use ":value" placeholder for rule's value in your message.
        If you need text ":value" in your message, use !:value to cancel (to escape) special purpose of this placeholder (see return messages).

### Return Values

Method `validate()` returns one of two types:

AN ARRAY containing in keys names of form fields and in values nested associative array containing in keys validation rules and in values error messages. (See below exampe).

or

A BOOLEAN value of TRUE if the validation was successful.

**Return values examples:**

The result may be (if all fields were sent empty):
```
Array
(
    [name] => Array
        (
            [required] => Поле обязательно для заполнения
            [minLength] => Минимально :value []%#^$&@#~!@#$%^&*( допустимоооооо 4 символов
            [alnum] => Allow only letters and digits
            [equal] => Not equils for okOk
        )

    [password] => Array
        (
            [required] => Поле обязательно для заполнения
            [minLength] => Minimally allowable 4 characters
            [alpha] => Allow only letters
            [boolean] => Entered value of this field must be boolean - 1 or 0
            [equal] => Пароль должен равняться этому значению - Paroli
        )

    [email] => Array
        (
            [required] => This field is required
            [email] => Invalid email address
        )

)
```

Or if all fields was checked successfully:

```php
1
```

### Available rules:

```
- requred       //Checks if field's value is not empty
- minLength     //Checks if the number of characters of field's value not less than rule value (UTF-8)
- maxLength     //Checks if the number of characters of field's value not greater than rule value (UTF-8)
- email         //Checks if field's value is a valid email adress
- numeric       //Checks if field contains only numeric value
- boolean       //Checks if field's value is boolean
- alpha         //Checks if field's value contains only alphabetic characters (UTF-8)
- alnum         //Checks if field's value contains only alphabetic and numeric characters (UTF-8)
```

### Creating your own validating rules

```php
$data = array (
    'name' => 'ok'
);

$rules = array(
    'name' => array(
        'between' => '5,10' // Custom rule, defined below
    )
);

$messages = array(
    'name' => array(
        'between' => 'The length of the value must be between :value'
    )
);

// Custom rule Callback Function for your needs

//First arg - your value from $rules array - '5,10'
//Second arg - field's value, which comes as first arg in validate() method - 'ok'

$validator->addRule('between', function($ruleValue, $fieldValue) {

    $arr = explode(',', $ruleValue);
    $lenghtFieldValue = mb_strlen($fieldValue, 'UTF-8');

    if (($lenghtFieldValue > $arr[0]) && ($lenghtFieldValue < $arr[1])) {
        return true;
    }
    return false;
});

$result = $validator->validate($data, $rules, $messages);
```

## Contributing

###//TODO

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

Alex Duplii - Boomerang

Email - dusanea@gmail.com

-----

Inspired by:

- http://jqueryvalidation.org/
- https://github.com/selahattinunlu/phpValidator
- https://github.com/Wixel/GUMP

## Copyright and License

Copyright 2014 Boomerang, Inc. under the MIT license.
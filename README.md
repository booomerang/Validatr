#Validatr

Validatr is a simple Php Validator for your projects.

  - Simple form for rules and errors messages
  - Flexible and extendable with your callbacks rules functions
  - Enjoyment

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

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FORM</title>
</head>
<body>

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

</body>
</html>

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

The result may be (if all fields were sent empty):
```php
Array
(
    [name] => Array
        (
            [required] => Поле обязательно для заполнения
            [minLength] => Минимально :value []%#^$&@#~!@#$%^&*( допустимоооооо 4 символов
            [equal] => PHPVALIDATOR!!!
        )

    [password] => Array
        (
            [required] => Поле обязательно для заполнения
            [minLength] => ок!
            [alpha] => Allow only letters
            [boolean] => Entered value of this field must be boolean - 1 or 0
            [equal] => Пароль должен равняться этому дерьму - Paroli
        )

    [email] => Array
        (
            [required] => This field is required
            [email] => Invalid email address
        )
```

or:

```php
1
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

## Copyright and License

Copyright 2014 Boomerang, Inc. under the MIT license.
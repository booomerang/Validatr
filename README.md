#Validatr 2.0

Validatr is a simple multilingual Php Validator library for checking user's data.

  - Simple form for rules and errors messages
  - Flexible and extendable with your callbacks rules functions
  - Enjoyment!

Validatr is a standalone PHP class, which can be extended for your needs.

What's new in 2.0 version?
- More rules
- More compact creating rules
- More powerful and flexible

## Table of contents

 - [Getting started](#getting-started)
 - [Installation](#installation)
 - [How to use](#how-to-use)
 - [Contributing](#contributing)
 - [Versioning](#versioning)
 - [Authors](#authors)
 - [Copyright and license](#copyright-and-license)

## Getting started

1. Php >= 5.3.0 is required
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
    <input type="text" name="name" id="name" /><br/>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" /><br/>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" /><br/>

    <label for="checkbox">Checkbox</label>
    <input type="hidden" name="checkbox" value="0" />
    <input type="checkbox" name="checkbox" id="checkbox" value="1" /><br/>

    <input type="submit" />
</form>
```

Form Handler with Validatr class

```php
// Set rules for some fields in form
$rules = array (
    'name' => 'required|min:3|max:15|equal:Awesome:[s]',
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
```

### Set messages

#### //Todo

### Return Values

Method `validate()` returns one of two types:

AN ARRAY containing in keys names of form fields and in values nested associative array containing in keys validation rules and in values error messages. (See below exampe).

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
- requred   //Checks if field's value is not empty
- min       //Checks if the number of characters of field's value not less than rule value (UTF-8)
- max       //Checks if the number of characters of field's value not greater than rule value (UTF-8)
- email     //Checks if field's value is a valid email adress
- numeric   //Checks if field contains only numeric value
- bool      //Checks if field's value is boolean
- alpha     //Checks if field's value contains only alphabetic characters (UTF-8)
- alnum     //Checks if field's value contains only alphabetic and numeric characters (UTF-8)
- alnumWith //Checks if field's value contains only alphabetic and numeric characters (UTF-8) and some else characters
- in        // Checks if value is included in the given list of values.
- notIn     // Checks if value is not included in the given list of values.
- equal     // checks if value is equal to rule value (Strict or not)
- notEqual  // checks if value is bot equal to rule value (Strict or not)
```

### Creating your own validating rules

#### //Todo

## Contributing

#### //Todo

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
- http://laravel.com/docs/validation

## Copyright and License

Copyright 2014 Boomerang, Inc. under the MIT license.
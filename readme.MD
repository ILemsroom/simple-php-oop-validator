# Ilems-Validator

Ilems-validator is a very simple PHP OOP validator library

It is to empower the user while staying concise and simple.

**Note:** this library requires php 8.0 and above to function im the right way

to use the lirary you will add an autoloader

# Adding Autoloader

```PHP
<?php
require 'vendor/autoload.php';
```

If your autoloader is not in the autoload file

```PHP
<?php
require 'path to autoloader/autoload.php';
```

# Usage

# Creating a validator instance

```PHP
<?php
require 'vendor/autoload.php';

$validator = new \Ilem\Validator\Validator;
```

# OR

```PHP
<?php
require 'vendor/autoload.php';
use Ilem\Validator\Validator;


$validator = new Validator;
```

# Validating

to validate any given date we first call the validate methode from the validator objcet

```PHP
<?php
$validator = new Validator;

$validator->validate($name, $value);
```

the validator stores it errors in the error property which can be accessed by

```PHP
<?php
$error = $validator->errors;
```

# method binding

The validator object works with method binding

```PHP
#lets validate an input with name username and a value
$validator =  new Validator;
#the first required() checkes if the value is empty
#used if a field is required
$validate->validate('username', $_POST['username'])->required();
#if the value is empty an error will be stored in the errors property
```

# checking min and max

min and max values or value lenght can be checked using the min() and max() methods
etc

```PHP
#lets validate an input with name username and a value
$validator =  new Validator;
#the mean takes in an integer as the parameter
$validate->validate('username', $_POST['username'])->min(5);
#if the value is less than 5 an error will be stored in the errors property
#for max
$validate->validate('username', $_POST['username'])->max(5);
#we can use all thress as
$validate->validate('username', $_POST['username'])->required()
    ->min(4)->max(12);
```

# working with Emails

to validate an email we use the isEmail() method

```PHP
#lets validate an input with name email and a value
$validator =  new Validator;
$validate->validate('email', $_POST['email'])->required()->isEmail();
```

# working with password

password using the above methods as well but with two extra methods
secure() and like() methods are password specific but the like() method can be used for
other form of validation

```PHP
#lets validate an input with name password and a value
$validator =  new Validator;
$validate->validate('password', $_POST['email'])->required()->secure();
#to confirm your password
$validator->validate('confirm_password', $_POST['confirm_password'])->required()
    ->like($_POST['password'], 'password');
```

**NOTE:** to store the value in the $Validator->data property you bind the store method
to the end of the chain

```PHP
$validator =  new Validator;
$validate->validate('username', $_POST['username'])->required()
    ->min(4)->max(12)->store();
```

## working with database

to check if a file exist in the datebase we must first confiqure the datebase setting

```PHP
$validator =  new Validator;
$validator->dbConfig('hostname', 'username', 'password', 'port');
$validator->setDatabase('database name');
```
the database confiquration should be made before the validation itself

# check if data exist in the database
to perform this action we bind the unique() methodr to the chain

```PHP
$validator =  new Validator;
$validator->dbConfig('hostname', 'username', 'password', 'port');
$validator->setDatabase('database name');

$validate->validate('username', $_POST['username'])->required()
    ->min(4)->max(12)->unique('table name', 'column name')->store();
```
## Default error messages
default messages is passed as the last or only parameter to the chained method except 
for the secure method

# other methods include
```PHP
clean() #clean method accepts an array of symbols to be cleaned from the value
#else all none alphanumeric symbols will be removed

is_unique('table name', 'column name', 'optional value')
```
returns true if the data exist and false if it does not
the is_unique() method is not used for validation as it cannot undergo binding
License
-------
MIT, see LICENSE.
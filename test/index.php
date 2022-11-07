<?php

use Ilem\Validator\Validator;

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (isset($_POST['submit'])) {
    $validator = new Validator;
    $validator->dbConfig('localhost', 'root', '');
    $validator->setDatabase('maxiapp');



    echo "<pre>";
    $validator->validate('testname', $_POST['testname'])
        ->required()->min(4)->max(7)->unique('users', 'username')->store();
    $validator->validate('email', $_POST['email'])->required()
        ->isEmail()->store();
    var_dump($validator->errors);
    var_dump($validator->data);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
        <input type="text" name="testname" id="">
        <input type="email" name="email" id="">
        <input type="submit" value="submit" name="submit">
    </form>
</body>

</html>
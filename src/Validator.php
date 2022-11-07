<?php

namespace Ilem\Validator;

/**
 * Description of Database
 *
 * @author ilem
 */

class Validator
{
    public $errors = [];

    protected $artribute;

    protected $value;

    public array $data = [];

    private \Ilem\Validator\ValidatorDatabaseHandler $dbHandler;

    public function __construct()
    {
        $this->dbHandler = new ValidatorDatabaseHandler;
    }

    private array $clean_chars = [
        '~', '`', '@', '!', '#', '$', '%', '^', '&', '*', '(', ')', '_',
        '-', '+', '=', '[', ']', '{', '}', '\'', ':', '"', ';', '>', '<',
        '.', ',', '/', '|', '\\', '?'
    ];

    /**
     * validate
     *the first method to be called, it takes and values to be validated
     * @param string $artribute
     * @param [type] $value
     * @return \Ilem\Validator\Validator
     */
    public function validate(string $artribute, $value): \Ilem\Validator\Validator
    {
        $this->artribute = $artribute;
        $this->errors[$artribute] = '';
        $this->value = $value;
        return $this;
    }

    /**
     * this method makes sure a values is given or not empty
     *
     * @param string $message
     * @return \Ilem\Validator\Validator
     */
    public function required($message = ''): \Ilem\Validator\Validator
    {
        if (empty($this->value) || trim($this->value, ' ') == '') {
            $this->setError('This field is required', $message);
        }
        return $this;
    }

    /**
     * min validates the value and make sure it is not less than the indicated value
     *
     * @param integer $min
     * @param string $message
     * @return \Ilem\Validator\Validator
     */
    public function min(int $min, string $message = ''): \Ilem\Validator\Validator
    {
        if (empty($this->errors[$this->artribute]) && strlen($this->value) < $min) {
            $this->setError('this field is should me more than ' .
                $min . ' characters', $message);
        }
        return $this;
    }

    /**
     * max validates the value and make sure it is not more than the indicated value
     *
     * @param integer $max
     * @param string $message
     * @return \Ilem\Validator\Validator
     */
    public function max(int $max, string $message = ''): \Ilem\Validator\Validator
    {
        if (empty($this->errors[$this->artribute]) && strlen($this->value) > $max) {
            $this->setError('this field is should not me more than ' .
                $max . ' characters', $message);
        }
        return $this;
    }

    /**
     * isEmail tells the validator class to check for a valid emaill
     *
     * @param string $message
     * @return \Ilem\Validator\Validator
     */
    public function isEmail($message = ''): \Ilem\Validator\Validator
    {
        if (empty($this->errors[$this->artribute])) {
            if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
                $this->setError('invalid Email', $message);
            }
        }
        return $this;
    }

    /**
     * this makes sure that the value is equal to an indicated value
     *
     * @param mixed $value
     * @param string $name
     * @param string $message
     * @return \Ilem\Validator\Validator
     */
    public function like(mixed $value, string $name, string $message = ''): \Ilem\Validator\Validator
    {
        if (empty($this->errors[$this->artribute]) && $this->value !== $value) {
            $this->setError($name . ' do not match', $message);
        }
        return $this;
    }

    /**
     * is_unique()
     * checks if a data exist in the database, if a data is found in the database
     * it returns true--else false
     *
     * @param string $table
     * @param string $column
     * @param mixed $Value
     * @return boolean
     */
    public function is_unique(string $table, string $column, mixed $value=''): bool
    {
        if (empty($this->dbHandler->dbname)) {
        } else {
            $conn = $this->dbHandler->createConnection();
            if ($conn) {
                try {
                    $sql = "SELECT * FROM $table WHERE $column = :$column";
                    $stmt = $conn->prepare($sql);
                    if(empty($vlaue)){
                        $select = $stmt->execute([$column => htmlentities($this->value)]);
                    }else{
                        $select = $stmt->execute([$column => htmlentities($value)]);
                    }
                   
                    if ($select) {
                        $user =  $stmt->fetch(\PDO::FETCH_ASSOC);
                        var_dump($user);
                        if($user){
                           return true;
                        }else{
                            return false;
                        }
                    } else {
                        return false;
                    }
                } catch (\Exception $ex) {
                    echo '<Pre>';
                    var_dump($ex);
                    return false;
                }
            }
        }
    }

    public function unique(string $table, string $column, $message=''): \Ilem\Validator\Validator
    {
        if (empty($this->dbHandler->dbname)) {
        } else {
            $conn = $this->dbHandler->createConnection();
            if ($conn) {
                try {
                    $sql = "SELECT * FROM $table WHERE $column = :$column";
                    $stmt = $conn->prepare($sql);

                    $select = $stmt->execute([$column => htmlentities($this->value)]);
                    if ($select) {
                        $user =  $stmt->fetch(\PDO::FETCH_ASSOC);
                        var_dump($user);
                        if($user){
                            $this->setError($this->artribute.' is taken', $message);
                        }
                    } else {
                    }
                } catch (\Exception $ex) {
                    echo '<Pre>';
                    var_dump($ex);
                }
                return $this;
            }
        }
    }

    /**
     * checks the value should be used for passwords
     *
     * @return \Ilem\Validator\Validator
     */
    public function secure(): \Ilem\Validator\Validator
    {
        $value = $this->value;
        if (empty($this->errors[$this->artribute])) {
            if (!preg_match("/[A-Z]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain atleast one uppercase latter');
            } elseif (!preg_match("/[a-z]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain atleast one lowercase latter');
            } elseif (!preg_match("/[0-9]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain atleast one number');
            }
        }
        return $this;
    }

    /**
     * removes all symbols from the input
     *the symbols can be specified as a parameter or all symbols will be removed
     * @return \Ilem\Validator\Validator
     */
    public function clean(array $symbols = []): \Ilem\Validator\Validator
    {
        if (empty($symbols) || $symbols === []) {
            $this->value = str_replace($this->clean_chars, '', $this->value);
        } else {
            $this->value = str_replace($symbols, '', $this->value);
        }

        return $this;
    }

    public function alphaNumeric(): \Ilem\Validator\Validator
    {
        $value = $this->value;
        if (empty($this->errors[$this->artribute])) {
            if (preg_match("/[^A-Za-z0-9\s]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must be alphanumeric');
            } elseif (!preg_match("/[a-z]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must be alphanumeric');
            } elseif (!preg_match("/[0-9]/", $value)) {
                $this->setError(default_message: $this->artribute . '  must be alphanumeric');
            } else {
            }
        }
        return $this;
    }

    public function numeric()
    {
        $value = $this->value;
        if (empty($this->errors[$this->artribute])) {
            if (preg_match("/[A-Z]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain only numbers');
            } elseif (preg_match("/[a-z]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain only numbers');
            } elseif (preg_match("/[^A-Za-z0-9\s]/", $value)) {
                $this->setError(default_message: $this->artribute . ' must contain only numbers');
            } else {
            }
        }
        return $this;
    }

    /**
     * saves the data to a data property
     *
     * @return void
     */
    public function store(): void
    {
        if (empty($this->errors[$this->artribute])) {
            $this->data[$this->artribute] = $this->value;
        }
    }

    private function setError(string $default_message, string $message = '')
    {
        if (empty($message) || $message === '') {
            $this->errors[$this->artribute] = $default_message;
        } else {
            $this->errors[$this->artribute] = $message;
        }
    }

    public function dbConfig(
        string $hostname,
        string $username,
        string $password,
        int $port = 3306
    ) {
        $this->dbHandler->setConfig($hostname, $username, $password, $port);
    }

    public function driver(string $driver)
    {
        $this->dbHandler->driver($driver);
    }

    public function setDatabase(string $database)
    {
        $this->dbHandler->dbname = $database;
    }
}

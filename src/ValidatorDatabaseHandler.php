<?php

namespace Ilem\Validator;

/**
 * Description of Database
 *
 * @author ilem
 */

class ValidatorDatabaseHandler
{


    private $model;

    private array $config = [];

    private string $driver = 'mysql';

    public string $dbname = '';

    public function setConfig(
        string $hostname,
        string $username,
        string $password,
        int $port = 3306
    ) {
        $this->config['hostname'] = $hostname;
        $this->config['username'] = $username;
        $this->config['password'] = $password;
        $this->config['port'] = $port;
    }

    public function driver(string $driver)
    {
        $this->driver = $driver;
    }


    public function createConnection():object|false
    {
        try {
            $conn = new \PDO(
                $this->dsn(),
                $this->config['username'],
                $this->config['password'],
                array(\PDO::ATTR_PERSISTENT)
            );
            if($conn){
                return $conn;
            }else{
                return false;
            }
        } catch (\Exception $ex) {
            echo "implement 505 in database conn";
            return false;
        }
    }

    private function dsn():string
    {
        return "mysql:hostname=" . $this->config['hostname'] .
            ";dbname=" . $this->dbname .
            ";port=" . $this->config['port'] . "";
    }
}

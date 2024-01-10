<?php

namespace app\core\database;

use app\core\interfaces\IPDOConfigFactory;
use PDO;

class MysqlConfig implements IPDOConfigFactory
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;

    public function __construct(string $host, string $database, string $username, string $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->username=  $username;
        $this->password = $password;
    }

    /**
     * Returns PDO connection
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return new PDO($this->getDsn(), $this->username, $this->password);
    }

    /**
     * Return a data source name for PDO
     * @param string $adapter
     * @return string
     */
    public function getDsn(string $adapter = self::PDO_MYSQL): string
    {
        return "$adapter:host=$this->host;dbname=$this->database";
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
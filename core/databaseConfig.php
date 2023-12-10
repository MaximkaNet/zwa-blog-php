<?php
namespace app\core;

class DatabaseConfiguration
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
     * Return a data source name for PDO
     * @param string $adapter
     * @return string
     */
    public function toDsn(string $adapter): string
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
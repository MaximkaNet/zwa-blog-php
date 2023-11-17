<?php

namespace app\core\orm;

use app\core\orm\exceptions\ExceptionModel;

class MysqlProvider
{
    private string|null $host;
    private string|null $username;
    private string|null $password;
    private string|null $database;
    private int|null $port;
    private string|null $socket;
    private array $models;

    public function __construct(
        string $host = null,
        string $username = null,
        string $password = null,
        string $database = null,
        int    $port = null,
        string $socket = null
    )
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
        $this->socket = $socket;
    }

    public function define(string $model_name, array $columns, array $options = null): MysqlModel
    {
        $model = new MysqlModel($model_name, $columns, $options);
        $this->models[$model_name] = $model;
        return $model;
    }

    public function model(string $name): MysqlModel
    {
        if(isset($this->models[$name]))
            return $this->models[$name];
        throw ExceptionModel::NotFound();
    }

    public function createSchema(string $model_name)
    {

    }

    public function sync()
    {

    }
}
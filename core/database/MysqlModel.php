<?php
namespace app\core\orm;
class MysqlModel
{
    private string $name;
    private string $table_name;
    private array $scheme;
    private bool $timestamps;

    public function __construct(string $name, array $scheme, array $options = null)
    {
        $this->name = $name;
        $this->table_name = $name . "s";
        $this->scheme = $scheme;

        // Setup options
        if(!isset($options))
            return;

        $this->timestamps = $options["timestamps"] ?? true;
    }

    public function hasMany(MysqlModel $model, array $options = null)
    {

    }

    public function hasOne(MysqlModel $model, array $options = null)
    {

    }

    public function create(array $values)
    {

    }

    public function destroy(array $where)
    {

    }

    public function findOne(array $where, array $options = null): array
    {
        $query = "SELECT * FROM `$this->nable_name` LIMIT 1";
    }
}
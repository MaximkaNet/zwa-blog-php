<?php

namespace app\core\interfaces;

interface IPDOConfigFactory
{
    /**
     * PHP Data Object (PDO) mysql driver
     */
    public const PDO_MYSQL = "mysql";

    /**
     * Make a specific data source name for PDO
     * @param string $adapter
     * @return string
     */
    public function getDsn(string $adapter): string;

    /**
     * Get username
     * @return string
     */
    public function getUsername(): string;

    /**
     * Get password
     * @return string
     */
    public function getPassword(): string;
}
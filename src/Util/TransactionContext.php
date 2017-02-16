<?php

namespace TnTest\Util;

use Doctrine\DBAL\Connection;

/**
 * Class TransactionContext is needed:
 * - as a layer of indirection between infrastructure layer (SQL) and business transaction
 * - as an incapsulation of possible multiple database connections,
 *      since it was not said that automoderation history and ad are the same databases
 *
 * @package TnTest\Util
 */
class TransactionContext
{
    /**
     * @var Connection
     */
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function begin()
    {
        $this->conn->beginTransaction();
    }

    public function commit()
    {
        $this->conn->commit();
    }
}
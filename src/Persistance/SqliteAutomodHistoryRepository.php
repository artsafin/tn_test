<?php


namespace TnTest\Persistance;


use Doctrine\DBAL\Connection;
use TnTest\Domain\AutomodResult;
use TnTest\Domain\Data\AutomodHistoryRepository;

class SqliteAutomodHistoryRepository implements AutomodHistoryRepository
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function persist(AutomodResult $result)
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->insert('automod_history')->values([
                                                   'ad_id'       => '?',
                                                   'ts'          => '?',
                                                   'is_passed'   => '?',
                                                   'fail_reason' => '?',
                                               ]);

        $qb->setParameters([$result->getAdId(),
                            $result->getTimestamp()->getTimestamp(),
                            (int)$result->isPassed(),
                            $result->getFailReason()]);

        $qb->execute();
    }
}
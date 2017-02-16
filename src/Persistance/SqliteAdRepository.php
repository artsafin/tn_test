<?php


namespace TnTest\Persistance;


use Doctrine\DBAL\Connection;
use Money\Money;
use TnTest\Domain\Ad;
use TnTest\Domain\Data\AdRepository;

class SqliteAdRepository implements AdRepository
{
    const TABLE = 'ad';

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

    public function updateStatus(Ad $ad)
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->update(self::TABLE)
           ->set('status', ':newStatus')
           ->where('id = :id');
        $qb->setParameters([':newStatus' => $ad->getStatusString(), ':id' => $ad->getId()]);

        $qb->execute();
    }

    /**
     * @param $phone
     *
     * @return int
     */
    public function countByPhone($phone)
    {
        $_t  = self::TABLE;
        $sql = <<<SQL
select count(*)
from {$_t} as t
where t.owner_phone = :ownerPhone
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ownerPhone' => $phone]);

        return $stmt->fetchColumn();
    }

    /**
     * @param int    $selfId
     * @param string $address
     * @param int    $floor
     * @param int    $numRooms
     * @param float  $totalSquare
     * @param Money  $price
     *
     * @return int
     */
    public function countByParams($selfId, $address, $floor, $numRooms, $totalSquare, Money $price)
    {
        $_t  = self::TABLE;
        $sql = <<<SQL
select count(*)
from {$_t} as t
where t.address = :address
and t.floor = :floor
and t.num_rooms = :numRooms
and t.total_square = :totalSquare
and t.price = :price
and t.id != :selfId
SQL;


        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
                           ':selfId'      => $selfId,
                           ':address'     => $address,
                           ':floor'       => $floor,
                           ':numRooms'    => $numRooms,
                           ':totalSquare' => $totalSquare,
                           ':price'       => $price->getAmount(),
                       ]);

        return $stmt->fetchColumn();
    }

    public function add(Ad $ad)
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->insert(self::TABLE)->values([
                                             'address'      => '?',
                                             'owner_name'   => '?',
                                             'owner_phone'  => '?',
                                             'status'       => '?',
                                             'ad_type'      => '?',
                                             'price'        => '?',
                                             'seller_type'  => '?',
                                             'floor'        => '?',
                                             'total_floors' => '?',
                                             'num_rooms'    => '?',
                                             'total_square' => '?',
                                             'description'  => '?',
                                             'source'       => '?',
                                         ]);

        $qb->setParameters([
                               $ad->getAddress(),
                               $ad->getOwnerName(),
                               $ad->getOwnerPhone(),
                               $ad->getStatusString(),
                               $ad->getAdType(),
                               $ad->getPrice()->getAmount() / 100,
                               $ad->getSellerType(),
                               $ad->getFloor(),
                               $ad->getTotalFloors(),
                               $ad->getNumRooms(),
                               $ad->getTotalSquare(),
                               $ad->getDescription(),
                               $ad->getSource(),
                           ]);

        $qb->execute();

        $lastInsertId = (int)$this->conn->query("SELECT last_insert_rowid()")->fetchColumn();

        return $ad->withId($lastInsertId);
    }
}
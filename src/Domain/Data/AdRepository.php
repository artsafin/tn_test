<?php


namespace TnTest\Domain\Data;

use Money\Money;
use TnTest\Domain\Ad;

interface AdRepository
{
    public function add(Ad $ad);

    /**
     * @param Ad $ad
     *
     * @return void
     */
    public function updateStatus(Ad $ad);

    /**
     * @param $phone
     *
     * @return int
     */
    public function countByPhone($phone);

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
    public function countByParams($selfId, $address, $floor, $numRooms, $totalSquare, Money $price);
}
<?php


namespace TnTest\App\Criteria;


use TnTest\Domain\Ad;
use TnTest\Domain\Criteria;
use TnTest\Domain\Data\AdRepository;

class Duplicate implements Criteria
{
    /**
     * @var AdRepository
     */
    private $adRepo;

    /**
     * @param AdRepository $adRepo
     */
    public function __construct(AdRepository $adRepo)
    {
        $this->adRepo = $adRepo;
    }

    /**
     * @param Ad $ad
     *
     * @return bool
     */
    function test(Ad $ad)
    {
        // адресом, этажом, числом комнат, общей площадью и ценой.
        $numDup = (int)$this->adRepo->countByParams($ad->getId(),
                                                    $ad->getAddress(),
                                                    $ad->getFloor(),
                                                    $ad->getNumRooms(),
                                                    $ad->getTotalSquare(),
                                                    $ad->getPrice());

        return $numDup == 0;
    }
}
<?php


namespace TnTest\App\Criteria;


use TnTest\Domain\Ad;
use TnTest\Domain\Criteria;
use TnTest\Domain\Data\AdRepository;

class OwnerHasTooMuchAds implements Criteria
{
    /**
     * @var
     */
    private $adLimit;
    /**
     * @var AdRepository
     */
    private $adRepo;

    /**
     * @param int          $adLimit
     * @param AdRepository $adRepo
     */
    public function __construct($adLimit, AdRepository $adRepo)
    {
        $this->adLimit   = $adLimit;
        $this->adRepo    = $adRepo;
    }

    /**
     * @param Ad $ad
     *
     * @return bool
     */
    function test(Ad $ad)
    {
        if ($ad->isOwner()) {
            $numByOwner = $this->adRepo->countByPhone($ad->getOwnerPhone());

            return $numByOwner < $this->adLimit;
        }

        return true;
    }
}
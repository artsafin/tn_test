<?php

namespace TnTest\App;

use TnTest\App\Criteria\Duplicate;
use TnTest\App\Criteria\OwnerHasTooMuchAds;
use TnTest\App\Criteria\PhoneBlacklisted;
use TnTest\App\Criteria\ResellerPretendsOwner;
use TnTest\App\Criteria\StopWord;
use TnTest\App\Exception\InvalidSourceException;
use TnTest\Domain\AdEnums;
use TnTest\Domain\Data\AdRepository;
use TnTest\Domain\Source\CriteriaRuledSource;
use TnTest\Domain\Source\Source;
use TnTest\Domain\Source\SourceFactory;

/**
 * Class MockSourceFactory
 * - can be implemented as database source
 * @package TnTest\App
 */
class MockSourceFactory implements SourceFactory
{
    /**
     * @var string
     */
    private $stopWordFileName;

    /**
     * @var AdRepository
     */
    private $adRepo;
    /**
     * @var string
     */
    private $phoneBlFileName;

    /**
     * @param string       $phoneBlFileName
     * @param string       $stopWordFileName
     * @param AdRepository $adRepo
     */
    public function __construct($phoneBlFileName, $stopWordFileName, AdRepository $adRepo)
    {
        $this->stopWordFileName = $stopWordFileName;
        $this->adRepo           = $adRepo;
        $this->phoneBlFileName  = $phoneBlFileName;
    }


    /**
     * @param string $sourceCode
     *
     * @return Source
     * @throws InvalidSourceException
     */
    public function createBySourceCode($sourceCode)
    {
        if ($sourceCode === AdEnums::SOURCE_1) {
            return new CriteriaRuledSource([
                                               new StopWord($this->stopWordFileName),
                                               new ResellerPretendsOwner("продажа от собственника"),
                                               new OwnerHasTooMuchAds(5, $this->adRepo),
                                               new Duplicate($this->adRepo)
                                           ]);
        } else if ($sourceCode === AdEnums::SOURCE_2) {
            return new CriteriaRuledSource([
                                               new PhoneBlacklisted($this->phoneBlFileName),
                                               new StopWord($this->stopWordFileName),
                                               new ResellerPretendsOwner("продажа от собственника"),
                                               new OwnerHasTooMuchAds(5, $this->adRepo),
                                           ]);
        }

        throw new InvalidSourceException($sourceCode);
    }
}
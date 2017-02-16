<?php

namespace TnTest\Domain;

class AutomodResult
{
    /**
     * @var Criteria
     */
    private $failCriteria;

    /**
     * @var bool
     */
    private $isPassed;
    /**
     * @var Ad
     */
    private $ad;

    /**
     * @var \DateTimeInterface
     */
    private $ts;

    /**
     * @param Ad $ad
     * @param Criteria $failCriteria
     */
    private function __construct(Ad $ad, Criteria $failCriteria = null)
    {
        $this->failCriteria = $failCriteria;
        $this->isPassed     = $failCriteria === null;
        $this->ad           = $ad;
        $this->ts           = new \DateTimeImmutable();
    }


    public static function failWithCriteria(Ad $ad, Criteria $criteria)
    {
        return new self($ad, $criteria);
    }

    public static function success(Ad $ad)
    {
        return new self($ad);
    }

    /**
     * @return bool
     */
    public function isPassed()
    {
        return $this->isPassed;
    }

    /**
     * @return int
     */
    public function getAdId()
    {
        return $this->ad->getId();
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp()
    {
        return $this->ts;
    }

    public function getFailReason()
    {
        return $this->isPassed() ? null : get_class($this->failCriteria);
    }

    public function toString()
    {
        return sprintf("Passed: %s%s",
                       $this->isPassed() ? 'yes' : 'no',
                       $this->isPassed() ? "" : ", reason: {$this->getFailReason()}");
    }
}
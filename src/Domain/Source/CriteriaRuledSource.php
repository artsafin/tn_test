<?php


namespace TnTest\Domain\Source;

use TnTest\Domain\Ad;
use TnTest\Domain\AutomodResult;
use TnTest\Domain\Criteria;

class CriteriaRuledSource implements Source
{
    private $criterias;

    /**
     * Source1 constructor.
     *
     * @param Criteria[] $criterias
     */
    public function __construct($criterias)
    {
        $this->criterias = $criterias;
    }

    /**
     * @param Ad $ad
     *
     * @return AutomodResult
     */
    function moderate(Ad $ad)
    {
        foreach ($this->criterias as $criteria) {
            if (!$criteria->test($ad)) {
                return AutomodResult::failWithCriteria($ad, $criteria);
            }
        }

        return AutomodResult::success($ad);
    }
}
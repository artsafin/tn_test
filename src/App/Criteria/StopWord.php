<?php


namespace TnTest\App\Criteria;


use TnTest\Domain\Ad;
use TnTest\Domain\Criteria;

class StopWord implements Criteria
{
    use LineReader;

    /**
     * @param Ad $ad
     *
     * @return bool
     */
    function test(Ad $ad)
    {
        $this->maybeLoadItems();

        foreach ($this->items as $item) {
            if ($ad->descriptionContains($item)) {
                return false;
            }
        }

        return true;
    }
}
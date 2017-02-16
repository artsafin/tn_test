<?php


namespace TnTest\App\Criteria;


use TnTest\Domain\Ad;
use TnTest\Domain\Criteria;

class PhoneBlacklisted implements Criteria
{
    use LineReader;

    function test(Ad $ad)
    {
        $this->maybeLoadItems();

        foreach ($this->items as $item) {
            if ($ad->ownerPhoneMatches($item)) {
                return false;
            }
        }

        return true;
    }
}
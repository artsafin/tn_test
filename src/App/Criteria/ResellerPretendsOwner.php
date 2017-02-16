<?php


namespace TnTest\App\Criteria;


use TnTest\Domain\Ad;
use TnTest\Domain\Criteria;

class ResellerPretendsOwner implements Criteria
{
    /**
     * @var
     */
    private $ownerWord;

    /**
     * @param string $ownerWord
     */
    public function __construct($ownerWord)
    {
        $this->ownerWord = $ownerWord;
    }

    /**
     * @param Ad $ad
     *
     * @return bool
     */
    function test(Ad $ad)
    {
        return !$ad->descriptionContains($this->ownerWord) || !$ad->isReseller();
    }
}
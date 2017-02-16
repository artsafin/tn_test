<?php


namespace TnTest\Domain\Source;

use TnTest\Domain\Ad;
use TnTest\Domain\AutomodResult;

interface Source
{
    /**
     * @param Ad $ad
     *
     * @return AutomodResult
     */
    function moderate(Ad $ad);
}
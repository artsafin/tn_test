<?php


namespace TnTest\Domain;


interface Criteria
{
    /**
     * @param Ad $ad
     *
     * @return bool
     */
    function test(Ad $ad);
}
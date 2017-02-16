<?php


namespace TnTest\Domain\Data;


use TnTest\Domain\AutomodResult;

interface AutomodHistoryRepository
{
    public function persist(AutomodResult $result);
}
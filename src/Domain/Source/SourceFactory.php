<?php


namespace TnTest\Domain\Source;


interface SourceFactory
{
    /**
     * @param string $sourceCode
     *
     * @return Source
     */
    function createBySourceCode($sourceCode);
}
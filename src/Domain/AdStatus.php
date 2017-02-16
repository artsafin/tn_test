<?php

namespace TnTest\Domain;


class AdStatus
{
    const AUTOMODERATION_PENDING = 1;
    const AUTOMODERATION_PASSED = 2;
    const AUTOMODERATION_FAILED = 3;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @param int $statusCode
     */
    private function __construct($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public static function automoderationPending()
    {
        return new self(self::AUTOMODERATION_PENDING);
    }

    public static function automoderationPassed()
    {
        return new self(self::AUTOMODERATION_PASSED);
    }

    public static function automoderationFailed()
    {
        return new self(self::AUTOMODERATION_FAILED);
    }

    function __toString()
    {
        return (string)$this->statusCode;
    }


}
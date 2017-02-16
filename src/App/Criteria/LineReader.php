<?php


namespace TnTest\App\Criteria;


use TnTest\App\Exception\LineReaderException;

trait LineReader
{
    private $listFilePath;
    private $items;

    /**
     * @param string $listFilePath
     */
    public function __construct($listFilePath)
    {
        $this->listFilePath = $listFilePath;
    }

    /**
     * Implementation depends on actual requirements:
     * If the file is assumed to be big, probably need a buffered read or roll out fancy search
     * IF not big, caching whole file in memory looks ok
     *
     * @throws LineReaderException
     */
    private function loadItems()
    {
        $this->items = file($this->listFilePath, FILE_IGNORE_NEW_LINES);
        if ($this->items === false) {
            throw new LineReaderException($this->listFilePath);
        }
        $this->items = array_filter(array_map('trim', $this->items));
    }

    private function maybeLoadItems()
    {
        if ($this->items === null) {
            $this->loadItems();
        }
    }
}
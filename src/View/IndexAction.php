<?php


namespace TnTest\View;


use Slim\Http\Request;
use Slim\Http\Response;

class IndexAction
{
    private $pageFileName;

    /**
     * IndexAction constructor.
     *
     * @param $pageFileName
     */
    public function __construct($pageFileName)
    {
        $this->pageFileName = $pageFileName;
    }

    function __invoke(Request $request, Response $response)
    {
        $response->getBody()->write(file_get_contents($this->pageFileName));

        return $response;
    }

}
<?php
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__).'/core/Request.php';


class RequestTest extends TestCase
{
    protected $request;

    public function setUp(): void
    {
        $this->request = new Request();
        $_SERVER['REQUEST_URI'] = '/foo/bar/list';
        $_SERVER['SCRIPT_NAME'] ='/foo/bar/index.php';
    }

    public function test_リクエストで送られてきたURLからbaseURLを取得する()
    {
        $baseURL = $this->request->getBaseURL();
        $this->assertEquals('/foo/bar',$baseURL);
    }

    public function test_リクエストで送られてきたURLからPATH_INFOを取得する()
    {
        $pathInfo = $this->request->getPathInfo();
        $this->assertEquals('/list',$pathInfo);
    }
}
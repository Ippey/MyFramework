<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testHello()
    {
        $_SERVER['REQUEST_URI'] = '/hello/ippei';
        ob_start();
        include 'public/index.php';
        $content = ob_get_clean();

        $this->assertEquals('Hello ippei', $content);
    }
}
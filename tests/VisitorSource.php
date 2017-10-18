<?php

namespace Domatskiy\Tests;

use Domatskiy\VisitorSource;

class VisitorSourceTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
 
    }
    public function tearDown()
    {
        
    }

    public function test()
    {
        $VisitorSource = VisitorSource::getInstance();

        $source = $VisitorSource->getSource();
        $this->assertEquals($source == null || $source instanceof VisitorSource\Source, true);



    }

}

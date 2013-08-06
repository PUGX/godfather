<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\Godfather;

Class GodfatherTest extends \PHPUnit_Framework_TestCase
{
    private $mock;
    private $godfather;

    public function setUp()
    {
        $this->godfather = new Godfather();

        $this->mock = $this->getMockBuilder('StdClass')
            ->setMethods(array('calling'))
            ->getMock();
        $this->mock->expects($this->once())
            ->method('calling')
            ->with('called');
    }

    function testMagicFunction()
    {
        $this->godfather->addStrategy('name', 'key', $this->mock);
        $this->godfather->getName('key')->calling('called');
    }

    function testMagicFunctionWithUnderscore()
    {
        $this->godfather->addStrategy('name_and_surname', 'key', $this->mock);
        $this->godfather->getNameAndSurname('key')->calling('called');
    }


}


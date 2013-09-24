<?php

namespace PUGX\GodfatherBundle\Tests\DependencyInjection;

use PUGX\GodfatherBundle\DependencyInjection\GodfatherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class GodfatherExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array()), $container);
        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
    }

    public function testContextCreated()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array('instanceA' => array('contexts' => array('manager' => array('interface' => 'interface'))))), $container);

        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.instanceA'), 'The godfather is loaded');

        $godfather = $container->getDefinition('godfather.instanceA');

        $found = false;
        foreach ($godfather->getMethodCalls() as $call) {
            if ($call[0] == 'addContext' && $call[1][0] == 'manager') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }


    public function testFallbackService()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array('instance' => array('contexts' => array('manager' => array('interface' => 'interface', 'fallback' => 'fallback'))))), $container);

        $this->assertTrue($container->hasDefinition('godfather.instance'), 'The godfather is loaded');
        $godfather = $container->getDefinition('godfather.instance');

        $found = false;
        foreach ($godfather->getMethodCalls() as $call) {
            if ($call[0] == 'addContext' && $call[1][0] == 'manager') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function testMultipleInstances()
    {

        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array($this->getFullConfigWithMultipleInstances()), $container);


        $this->assertFalse($container->hasDefinition('godfather.default'), 'default shouldn\'t be created');
        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');

        $godfather = $container->getDefinition('godfather');

        $found = false;
        foreach ($godfather->getMethodCalls() as $call) {
            if ($call[0] == 'addContext' && $call[1][0] == 'manager') {
                $found = true;
            }
        }
        $this->assertTrue($found, 'default not found');
        $godfather = $container->getDefinition('godfather.instance1');

        $found = false;
        foreach ($godfather->getMethodCalls() as $call) {
            if ($call[0] == 'addContext' && $call[1][0] == 'manager') {
                $found = true;
            }
        }
        $this->assertTrue($found, 'instance1 not found');
    }

    protected function getFullConfigWithMultipleInstances()
    {
        $yaml = <<<EOF
default:
    contexts:
        manager:
            interface: interface0
            fallback: fallback0
instance1:
    contexts:
        manager:
            interface: interface1
            fallback: fallback1
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}

<?php

namespace PUGX\GodfatherBundle\Tests\DependencyInjection;

use PUGX\GodfatherBundle\DependencyInjection\GodfatherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
        $loader->load(array(array('contexts' => array('manager'=>array('name'=>'manager', 'interface'=>'interface', 'fallback'=>'fallback')))), $container);

        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
        $godfather = $container->getDefinition('godfather');

        $found = false;
        foreach ($godfather->getMethodCalls() as $call) {
            if ($call[0] == 'addContext' && $call[1][0] == 'manager') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }
}

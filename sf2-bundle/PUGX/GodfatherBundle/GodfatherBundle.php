<?php
namespace PUGX\Godfather\GodfatherBundle;

use PUGX\GodfatherBundle\DependencyInjection\Compiler\CompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GodfatherBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CompilerPass());
    }
}

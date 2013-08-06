<?php

namespace PUGX\GodfatherBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('godfather')) {
            return;
        }

        $definition = $container->getDefinition(
            'godfather'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'godfather.strategy'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {

                if (empty($attributes['context_name']) || empty($attributes['context_key']) ) {
                    throw new \InvalidArgumentException(sprintf('The class or the name is not defined in the tag for the service "%s"', $id));
                }

                $definition->addMethodCall(
                    'addStrategy',
                    array($attributes["context_name"], $attributes["context_key"], new Reference($id))
                );
            }
        }
    }
}

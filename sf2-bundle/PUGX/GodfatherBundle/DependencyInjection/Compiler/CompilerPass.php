<?php

namespace PUGX\GodfatherBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(
            'godfather.strategy'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {

                if (empty($attributes['context_name']) || empty($attributes['context_key'])) {
                    throw new \InvalidArgumentException(sprintf('The class or the name is not defined in the tag for the service "%s"', $id));
                }

                $instanceName = null;
                if (!empty($attributes['instance']) && $attributes['instance'] != 'default') {
                    $instanceName = $attributes['instance'];
                }
                $definition = $this->getOrCreateDefinition($container, $instanceName);

                $definition->addMethodCall(
                    'addStrategy',
                    array($attributes["context_name"], $attributes["context_key"], new Reference($id))
                );
            }
        }
    }

    private function getOrCreateDefinition(ContainerBuilder $container, $name = null)
    {
        if (null == $name) {
            $name = 'godfather';
        } else {
            $name = sprintf("godfather.%s", $name);
        }

        if (!$container->hasDefinition($name)) {
            $definition = $this->createGodFatherDefinition();
            $container->setDefinition($name, $definition);

        } else {
            $definition = $container->getDefinition($name);
        }

        return $definition;
    }

    private function createGodFatherDefinition()
    {
        return new Definition('%godfather.class%', array("%godfather.context.class%"));
    }
}

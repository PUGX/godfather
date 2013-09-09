<?php

namespace PUGX\GodfatherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('godfather');

        $this->addContextSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the godfather.context configuration
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addContextSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('context')
            ->canBeUnset()
            ->children()
                ->arrayNode('contexts')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('interface')->defaultNull()->end()
                            ->scalarNode('fallback')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
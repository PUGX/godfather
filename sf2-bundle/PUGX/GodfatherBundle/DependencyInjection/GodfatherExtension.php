<?php

namespace PUGX\GodfatherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GodfatherExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config as $name => $instance) {
            $this->addInstance($name, $instance, $container);
        }
    }

    /**
     * Add an instance of Strategies.
     *
     * @param string $name The instance name
     * @param array $parameters Array of parameters.
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function addInstance($name, array $parameters, ContainerBuilder $container)
    {
        $instance = $this->getOrCreateDefinition($container, $name);

        $contexts = $parameters['contexts'];
        foreach ($contexts as $name => $context) {
            $this->addContext($instance, $name, $context, $container);
        }
    }

    /**
     * Add a Context.
     *
     * @param Definition $instance
     * @param string $name
     * @param array $context
     * @param ContainerBuilder $container
     */
    protected function addContext(Definition $instance, $name, array $context, ContainerBuilder $container)
    {
        $contextInterface = isset($context['interface']) ? $context['interface'] : null;
        $fallback = null;
        if (isset($context['fallback'])) {
            $fallback = new Reference($context['fallback']);
        }

        $instance->addMethodCall('addContext', array($name, $contextInterface, $fallback));
    }

    /**
     * Get or create the instance definition.
     *
     * @param ContainerBuilder $container
     * @param string $name
     * @return Definition
     *
     */
    private function getOrCreateDefinition(ContainerBuilder $container, $name = 'default')
    {
        if ('default' == $name) {
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

    /**
     * Definition Factory
     *
     * @return Definition
     */
    private function createGodFatherDefinition()
    {
        return new Definition('%godfather.class%', array("%godfather.context.class%"));
    }
}

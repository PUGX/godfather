<?php

namespace PUGX\Godfather;

Class Godfather
{
    private $contextClass;
    private $contexts = array();

    public function __construct($contextClass = 'PUGX\Godfather\Context')
    {
        $this->contextClass = $contextClass;
    }

    /**
     * Create a new context, strategy interface and fallback are optional.
     *
     * @param string $contextName
     * @param string $strategyInterface
     * @param mixed  $fallBackStrategy
     *
     * @return Godfather $this
     */
    public function addContext($contextName, $strategyInterface = null, $fallBackStrategy = null)
    {
        $this->contexts[$contextName] = $this->createContext($contextName, $strategyInterface, $fallBackStrategy);

        return $this;
    }

    /**
     * Get the contexts array
     *
     * @return ContextInterface[]
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * Simple Context factory.
     *
     * @param $contextName
     * @param $strategyInterface
     * @param $fallBackStrategy
     *
     * @return Context
     */
    public function createContext($contextName, $strategyInterface = null, $fallBackStrategy = null)
    {
        $class = $this->contextClass;
        return new $class($contextName, $strategyInterface, $fallBackStrategy);
    }

    /**
     * Add a strategy to a context.
     *
     * @param string $contextName
     * @param mixed $contextKey
     * @param mixed $strategy
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addStrategy($contextName, $contextKey, $strategy)
    {
        if (!isset($this->contexts[$contextName])) {
            $this->contexts[$contextName] = $this->createContext($contextName);
        }
        $this->contexts[$contextName]->addStrategy($contextKey, $strategy);

        return $this;
    }

    /**
     * Get the strategy for the key ($contextName, $context).
     *
     * @param string $contextName
     * @param mixed $context
     *
     * @return mixed
     */
    public function getStrategy($contextName, $context)
    {
        return $this->contexts[$contextName]->getStrategy($context);
    }

    /**
     * A string to underscore.
     *
     * @param string $id The string to underscore
     *
     * @return string The underscored string
     */
    public static function underscore($id)
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), strtr($id, '_', '.')));
    }

    /**
     * Magic for getStrategy.
     *
     * @todo improve add argument as first element, replacing array_merge
     * @param $method
     * @param $args
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);
        if (strcmp($prefix, 'get') != 0) {
            throw new \BadMethodCallException($method);
        }
        $methodName = substr($method, 3);
        $contextName = self::underscore($methodName);

        if (isset($this->contexts[$contextName])) {
            $args = array_merge(array($contextName), $args);

            return call_user_func_array(array($this, 'getStrategy'), $args);
        }

        throw new \BadMethodCallException($method);

    }
}


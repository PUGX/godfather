<?php

namespace PUGX\Godfather;

Interface ContextInterface
{
    /**
     * Create a new context, strategy interface and fallback are optional.
     *
     * @param string $name The name of the Context
     * @param string $strategyInterface The FQCN of the interface or class.
     * @param string $fallback    the fallback
     */
    public function __construct($name, $strategyInterface = null, $fallback = null);

    /**
     * Add a strategy to a context.
     *
     * @param mixed $key
     * @param mixed $strategy
     */
    public function addStrategy($key, $strategy);

    /**
     * Get the strategy for the key.
     *
     * @param mixed $key
     */
    public function getStrategy($key);
}
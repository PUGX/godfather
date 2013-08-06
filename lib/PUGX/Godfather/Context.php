<?php

namespace PUGX\Godfather;

class Context implements ContextInterface
{
    private $name;
    private $fallback;
    private $strategyInterface;
    private $strategies = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $strategyInterface = null, $fallback = null)
    {
        $this->name = $name;
        $this->strategyInterface = $strategyInterface;

        if (null !== $fallback && !$this->isRespectingStrategyInterface($fallback)) {
            throw new \InvalidArgumentException();
        }

        $this->fallback = $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function addStrategy($key, $strategy)
    {
        $key = $this->convertToKey($key);

        if (!$this->isRespectingstrategyInterface($strategy)) {
            throw new \InvalidArgumentException();
        }

        $this->strategies[$key] = $strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrategy($key)
    {
        $key = $this->convertToKey($key);

        if (!isset($this->strategies[$key]) && (null !== $this->fallback)) {
            return $this->fallback;
        }

        return $this->strategies[$key];
    }

    /**
     * Return true if the object is an instance of the strategyInterface.
     *
     * @param mixed $object
     *
     * @return Boolean
     */
    protected function isRespectingStrategyInterface($object)
    {
        if (null !== $this->strategyInterface) {
            return ($object instanceof $this->strategyInterface);
        }

        return true;
    }

    /**
     * Convert an object into a key.
     *
     * @param $mixed
     *
     * @return string
     */
    private function convertToKey($mixed)
    {
        if (is_object($mixed)) {
            return get_class($mixed);
        }

        try {
            $mixed = (string)$mixed;
        } catch (\Exception $e) {
            $mixed = gettype($mixed);
        }

        return $mixed;
    }
}
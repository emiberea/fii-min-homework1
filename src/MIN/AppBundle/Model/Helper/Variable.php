<?php

namespace MIN\AppBundle\Model\Helper;

class Variable
{
    /** @var string $name */
    private $name;

    /** @var array $values */
    private $values;

    /** @var boolean $isArray */
    private $isArray = true;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name . ($this->isArray ? '(i)' : '');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->isArray = true;
        $this->values = $values;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return array_key_exists(0, $this->values) ? $this->values[0] : null;
    }

    /**
     * @param array $value
     */
    public function setValue($value)
    {
        $this->isArray = false;
        $this->values = array($value);
    }
}

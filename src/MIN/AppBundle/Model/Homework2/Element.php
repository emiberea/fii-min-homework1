<?php

namespace MIN\AppBundle\Model\Homework2;

class Element
{
    const DIM = 5;

    /** @var Element[] $items */
    public static $items = array();

    /** @var array $items */
    public static $_mask = array(1, 2, 4, 8, 16);

    /** @var int $value */
    private $value;

    /** @var Element[] $landscape_F */
    private $landscape_F;

    /** @var Element[] $landscape_B */
    private $landscape_B;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->landscape_F = array();
        $this->landscape_B = array();
    }

    public static function init()
    {
        for ($i = 0; $i < 32; $i++) {
            self::$items[] = new Element($i);
        }

        foreach (self::$items as $item) {
            $item->computeHillCimbing();
        }
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getFitness()
    {
        return $this->computeFunction($this->value);
    }

    /**
     * @param int $x
     * @return mixed
     */
    private function computeFunction($x)
    {
        return $x * $x * $x - 60 * $x * $x + 900 * $x + 100;
    }

    public function computeHillCimbing()
    {
        // variant 1 - first improvement
        $this->addLandscapeElementOrPropagateIt_F($this);
        // variant 2 - best improvement
        $this->addLandscapeElementOrPropagateIt_B($this);
    }

    /**
     * @param Element $landscapeElement
     */
    private function addLandscapeElementOrPropagateIt_F(Element $landscapeElement = null)
    {
        if ($this->getFirstBetterNeighbor() == null) {
            if (!in_array($landscapeElement, $this->landscape_F)) {
                $this->landscape_F[] = $landscapeElement;
            }
        } else {
            $this->getFirstBetterNeighbor()->addLandscapeElementOrPropagateIt_F($landscapeElement);
        }
    }

    /**
     * @param Element $landscapeElement
     */
    private function addLandscapeElementOrPropagateIt_B(Element $landscapeElement = null)
    {
        if ($this->getBestNeighbor() == null) {
            if (!in_array($landscapeElement, $this->landscape_B)) {
                $this->landscape_B[] = $landscapeElement;
            }
        } else {
            $this->getBestNeighbor()->addLandscapeElementOrPropagateIt_B($landscapeElement);
        }
    }

    /**
     * @return Element[]
     */
    public function getNeighbors()
    {
        $neighbors = array();
        for ($i = 0; $i < self::DIM; $i++) {
            $neighbors[$i] = self::$items[($this->value ^ self::$_mask[$i])];
        }

        return $neighbors;
    }

    /**
     * cauta in vecinatate prima solutie candidat care e mai buna decat solutia curenta
     *
     * @return Element|null
     */
    public function getFirstBetterNeighbor()
    {
        for ($i = 0; $i < self::DIM; $i++) {
            if ($this->getFitness() < $this->getNeighbors()[$i]->getFitness()) {
                return $this->getNeighbors()[$i];
            }
        }

        return null;
    }

    /**
     * cauta in vecinatate cea mai buna solutie
     *
     * @return Element|null
     */
    public function getBestNeighbor()
    {
        $bestNeighbor = $this->getNeighbors()[0];
        for ($i = 1; $i < self::DIM; $i++) {
            if ($bestNeighbor->getFitness() < $this->getNeighbors()[$i]->getFitness()) {
                $bestNeighbor = $this->getNeighbors()[$i];
            }
        }

        if ($this->getFitness() < $bestNeighbor->getFitness()) {
            return $bestNeighbor;
        }

        return null;
    }

    public function getNeighborsAsBinaryString()
    {
        return $this->getElementsAsBinaryString($this->getNeighbors());
    }

    public function getNeighborsAsIntString()
    {
        return $this->getElementsAsIntString($this->getNeighbors());
    }

    public function getBits()
    {
        return decbin($this->value);
    }

    public function getLandscape_F_AsIntsString()
    {
        return $this->getElementsAsIntString($this->landscape_F);
    }

    public function getLandscape_B_AsIntsString()
    {
        return $this->getElementsAsIntString($this->landscape_B);
    }

    private function getElementsAsBinaryString($elements)
    {
        if ($elements == null || count($elements) == 0) {
            return "NULL";
        }

        $result = "";
        for ($i = 0; $i < count($elements); $i++) {
            $result .= sprintf("(%s), ", decbin($elements[$i]->value));
        }

        return trim(rtrim($result, ","));
    }

    private function getElementsAsIntString($elements)
    {
        if ($elements == null || count($elements) == 0) {
            return "NULL";
        }

        $result = "";
        for ($i = 0; $i < count($elements); $i++) {
            $result .= sprintf("%s, ", $elements[$i]->value);
        }

        return trim(rtrim($result, ","));
    }
}

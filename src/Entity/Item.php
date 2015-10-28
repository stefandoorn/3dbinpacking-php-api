<?php namespace BinPacking3d\Entity;

use BinPacking3d\Entity;
use BinPacking3d\EntityInterface;

/**
 * Class Item
 * @package BinPacking3d\Entity
 */
class Item implements EntityInterface
{

    /**
     * @var
     */
    private $width;

    /**
     * @var
     */
    private $height;

    /**
     * @var
     */
    private $depth;

    /**
     * @var int
     */
    private $quantity = 1;

    /**
     * @var bool
     */
    private $verticalRotationLock = false;

    /**
     * @var
     */
    private $itemIdentifier;

    /**
     * @var
     */
    private $weight;

    /**
     * @var
     */
    private $product;

    /**
     * @return array
     */
    public function render()
    {
        return [
            'w' => $this->getWidth(),
            'h' => $this->getHeight(),
            'd' => $this->getDepth(),
            'q' => $this->getQuantity(),
            'vr' => (int) $this->isVerticalRotationLock(),
            'id' => $this->getItemIdentifier(),
            'wg' => $this->getWeight()
        ];
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $items = [
            $this->getWidth(),
            $this->getHeight(),
            $this->getDepth(),
            $this->getQuantity(),
            $this->getItemIdentifier(),
            $this->getWeight()
        ];

        foreach($items as $item) {
            if($item === null || (is_numeric($item) && $item <= 0)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     * @return Item
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return Item
     */
    public function setWidth($width)
    {
        $this->width = (float) $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return Item
     */
    public function setHeight($height)
    {
        $this->height = (float) $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param mixed $depth
     * @return Item
     */
    public function setDepth($depth)
    {
        $this->depth = (float) $depth;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerticalRotationLock()
    {
        return $this->verticalRotationLock;
    }

    /**
     * @param boolean $verticalRotationLock
     * @return Item
     */
    public function setVerticalRotationLock($verticalRotationLock)
    {
        $this->verticalRotationLock = (bool) $verticalRotationLock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemIdentifier()
    {
        return $this->itemIdentifier;
    }

    /**
     * @param mixed $itemIdentifier
     * @return Item
     */
    public function setItemIdentifier($itemIdentifier)
    {
        $this->itemIdentifier = (string) $itemIdentifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return Item
     */
    public function setWeight($weight)
    {
        $this->weight = (float) $weight;
        return $this;
    }



}

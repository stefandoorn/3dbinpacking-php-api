<?php namespace BinPacking3d\Entity;

use BinPacking3d\Entity;
use BinPacking3d\EntityInterface;
use BinPacking3d\Exception\CriticalException;

/**
 * Class Bin
 * @package BinPacking3d\Entity
 */
class Bin implements EntityInterface
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
     * @var
     */
    private $identifier;

    /**
     * @var
     */
    private $internalIdentifier;

    /**
     * @var
     */
    private $maxWeight;

    /**
     * @var
     */
    private $outerWidth;

    /**
     * @var
     */
    private $outerHeight;

    /**
     * @var
     */
    private $outerDepth;

    /**
     * @var
     */
    private $weight;

    /**
     * @var
     */
    private $items;

    /**
     * @var
     */
    private $usedSpace;

    /**
     * @var
     */
    private $usedWeight;

    /**
     * @var
     */
    private $image;

    /**
     * @return array
     */
    public function render()
    {
        return [
            'w' => $this->getWidth(),
            'h' => $this->getHeight(),
            'd' => $this->getDepth(),
            'id' => $this->getIdentifier(),
            'max_wg' => $this->getMaxWeight()
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public final function validate()
    {
        $items = [
            $this->getWidth(),
            $this->getHeight(),
            $this->getDepth(),
            $this->getIdentifier(),
            $this->getMaxWeight(),
            $this->getInternalIdentifier()
        ];

        foreach ($items as $item) {
            if ($item === null || (is_numeric($item) && $item <= 0)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Bin
     */
    public function setImage($image)
    {
        $this->image = (string)$image;
        return $this;
    }

    /**
     * @param $path
     * @return bool|int
     */
    public function saveImage($path)
    {
        if (!$this->getImage()) {
            return false;
        }

        return file_put_contents($path, base64_decode($this->getImage()));
    }

    /**
     * @return mixed
     */
    public function getInternalIdentifier()
    {
        return $this->internalIdentifier;
    }

    /**
     * @param mixed $internalIdentifier
     * @return Bin
     */
    public function setInternalIdentifier($internalIdentifier)
    {
        $this->internalIdentifier = $internalIdentifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsedSpace()
    {
        return $this->usedSpace;
    }

    /**
     * @param mixed $usedSpace
     * @return Bin
     */
    public function setUsedSpace($usedSpace)
    {
        $this->usedSpace = (float)$usedSpace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsedWeight()
    {
        return $this->usedWeight;
    }

    /**
     * @param mixed $usedWeight
     * @return Bin
     */
    public function setUsedWeight($usedWeight)
    {
        $this->usedWeight = (float)$usedWeight;
        return $this;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return \Generator
     */
    public function yieldItems()
    {
        foreach ($this->items as $item) {
            yield $item;
        }
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
     * @return Bin
     */
    public function setWidth($width)
    {
        $this->width = (float)$width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOuterWidth()
    {
        return $this->outerWidth;
    }

    /**
     * @param mixed $outerWidth
     * @return Bin
     */
    public function setOuterWidth($outerWidth)
    {
        $outerWidth = (float)$outerWidth;

        if (!$this->getWidth() || $outerWidth < $this->getWidth()) {
            throw new CriticalException('Outer width should be bigger than inner width.');
        }

        $this->outerWidth = $outerWidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOuterHeight()
    {
        return $this->outerHeight;
    }

    /**
     * @param mixed $outerHeight
     * @return Bin
     */
    public function setOuterHeight($outerHeight)
    {
        $outerHeight = (float)$outerHeight;

        if (!$this->getHeight() || $outerHeight < $this->getHeight()) {
            throw new CriticalException('Outer height should be bigger than inner width.');
        }

        $this->outerHeight = $outerHeight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOuterDepth()
    {
        return $this->outerDepth;
    }

    /**
     * @param mixed $outerDepth
     * @return Bin
     */
    public function setOuterDepth($outerDepth)
    {
        $outerDepth = (float)$outerDepth;

        if (!$this->getDepth() || $outerDepth < $this->getDepth()) {
            throw new CriticalException('Outer depth should be bigger than inner width.');
        }

        $this->outerDepth = $outerDepth;
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
     * @return Bin
     */
    public function setWeight($weight)
    {
        $this->weight = (float)$weight;
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
     * @return Bin
     */
    public function setHeight($height)
    {
        $this->height = (float)$height;
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
     * @return Bin
     */
    public function setDepth($depth)
    {
        $this->depth = (float)$depth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     * @return Bin
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = (string)$identifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * @param mixed $maxWeight
     * @return Bin
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = (float)$maxWeight;
        return $this;
    }

}

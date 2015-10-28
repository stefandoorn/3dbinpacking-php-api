<?php namespace BinPacking3d\Entity;

use BinPacking3d\EntityInterface;

/**
 * Class Request
 * @package BinPacking3d\Entity
 */
class Request implements EntityInterface
{

    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $apiKey;

    /**
     * @var array
     */
    private $bins = array();

    /**
     * @var array
     */
    private $items = array();

    /**
     * @return array
     * @throws \Exception
     */
    public function render()
    {
        $this->validate();

        // Render bins
        $bins = [];
        foreach ($this->yieldBins() as $bin) {
            $bins[] = $bin->render();
        }

        // Render items
        $items = [];
        foreach ($this->yieldItems() as $item) {
            $items[] = $item->render();
        }

        return [
            'username' => $this->getUsername(),
            'api_key' => $this->getApiKey(),
            'bins' => $bins,
            'items' => $items
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate()
    {
        if ($this->getUsername() === null || $this->getApiKey() === null || empty($this->bins) || empty($this->items)) {
            throw new CriticalException('Not all required variables entered for rendering.');
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return Request
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     * @return Request
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return \Generator
     */
    private function yieldBins()
    {
        foreach ($this->bins as $bin) {
            yield $bin;
        }
    }

    /**
     * @return \Generator
     */
    private function yieldItems()
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function getItem($identifier)
    {
        return $this->items[$identifier];
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function getBin($identifier)
    {
        return $this->bins[$identifier];
    }

    /**
     * @param Item $item
     * @return $this
     */
    public function addItem(Item $item)
    {
        // Check for unique identifier
        if (array_key_exists($item->getItemIdentifier(), $this->items)) {
            throw new CriticalException('Identifier already exists');
        }

        // Check if we can validate it
        if (!$item->validate()) {
            throw new CriticalException('Cannot validate item settings, item: ' . print_r($item->render(), true));
        }

        // Add to store
        $this->items[$item->getItemIdentifier()] = $item;
        return $this;
    }

    /**
     * @param Bin $bin
     * @return $this
     */
    public function addBin(Bin $bin)
    {
        // Check for unique identifier
        if (array_key_exists($bin->getIdentifier(), $this->bins)) {
            throw new CriticalException('Identifier already exists');
        }

        // Check if we can validate it
        if (!$bin->validate()) {
            throw new CriticalException('Cannot validate bin settings, bin: ' . print_r($bin->render(), true));
        }

        // Add to store
        $this->bins[$bin->getIdentifier()] = $bin;
        return $this;
    }

    /**
     * @return array
     */
    public function getBins()
    {
        return $this->bins;
    }

    /**
     * @param array $bins
     * @return Request
     */
    public function setBins($bins)
    {
        $this->bins = $bins;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return Request
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }


}

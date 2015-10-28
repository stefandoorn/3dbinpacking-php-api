<?php namespace BinPacking3d\Entity;

use BinPacking3d\Exception\CriticalException;
use BinPacking3d\Exception\WarningException;

/**
 * Class Packed
 * @package BinPacking3d\Entity
 */
class Packed
{

    /**
     * @var array
     */
    private $bins = array();

    /**
     * @var int
     */
    private $errorCount = 0;

    /**
     * @param $response
     * @param Request $request
     * @throws \Exception
     */
    public function __construct(Response $response, Request $request)
    {
        // Parse response
        $response = $response->get()->response;

        // @todo
        if ($response->status !== 1 || !empty($response->errors)) {
            $this->errorCount = count($response->errors);

            $this->handleErrors($response->errors);
        }

        // Loop over packed bins
        foreach ($response->bins_packed as $packedBin) {
            // Find original bin
            $originalBin = $request->getBin($packedBin->bin_data->id);

            // Loop over items
            foreach ($packedBin->items as $item) {
                $originalItem = $request->getItem($item->id);
                $originalBin->addItem($originalItem);
            }

            // Add used space
            $originalBin->setUsedSpace($packedBin->bin_data->used_space);

            // Add used weight
            $originalBin->setUsedWeight($packedBin->bin_data->used_weight);

            // Set image result
            $originalBin->setImage($packedBin->image_complete);

            // Add bin
            $this->bins[] = $originalBin;
        }
    }

    /**
     * Throw most severe error as exception
     *
     * @param $errors
     * @throws CriticalException
     * @throws WarningException
     * @throws \Exception
     */
    public function handleErrors($errors)
    {
        // Sort based on severity
        $order = ['critical', 'warning'];
        usort($errors, function ($a, $b) use ($order) {
            if (array_search($a->level, $order) > array_search($b->level, $order)) {
                return -1;
            } elseif (array_search($a->level, $order) < array_search($b->level, $order)) {
                return 1;
            }

            return 0;
        });

        // Throw first one as exception
        $error = array_shift($errors);
        switch ($error->level) {
            case 'critical':
                throw new CriticalException($error->message);
                break;
            case 'warning':
                throw new WarningException($error->message);
                break;
            default:
                throw new \Exception($error->message);
                break;
        }
    }

    /**
     * @return int
     */
    public function errorCount()
    {
        return $this->errorCount;
    }

    /**
     * @return array
     */
    public function getBins()
    {
        return $this->bins;
    }

    /**
     * @return \Generator
     */
    public function yieldBins()
    {
        foreach ($this->bins as $bin) {
            yield $bin;
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->bins);
    }

}

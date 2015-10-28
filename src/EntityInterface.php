<?php namespace BinPacking3d;

/**
 * Interface EntityInterface
 * @package BinPacking3d
 */
interface EntityInterface {

    /**
     * @return mixed
     */
    public function render();

    /**
     * @return mixed
     */
    public function validate();

}

<?php namespace BinPacking3d\Entity;

/**
 * Class Response
 * @package BinPacking3d\Entity
 */
class Response
{

    private $data;

    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }

    public function get()
    {
        return $this->data;
    }
}

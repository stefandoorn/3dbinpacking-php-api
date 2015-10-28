<?php namespace BinPacking3d;

use BinPacking3d\Entity\Request;

/**
 * Interface QueryInterface
 * @package BinPacking3d
 */
interface QueryInterface
{

    public function __construct(Request $request, $params = [], $region = Query::REGION_GLOBAL);

}

<?php namespace BinPacking3d;

class Utils
{

    public static function noEmptyItems(array $items)
    {
        foreach ($items as $item) {

            if ($item === null || (is_numeric($item) && $item <= 0)) {

                return false;
            }
        }

        return true;
    }

}

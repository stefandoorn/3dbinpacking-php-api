<?php namespace BinPacking3d\Tests;

use PHPUnit_Framework_TestCase;

/**
 * Class BinPackingTestBase
 * @package BinPacking3d\Tests
 */
abstract class BinPackingTestBase extends PHPUnit_Framework_TestCase
{

    protected function getFilePath($file)
    {
        return __DIR__ . '/../_files/' . $file;
    }

}

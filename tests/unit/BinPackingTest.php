<?php namespace BinPacking3d\Tests;

use BinPacking3d\PackIntoMany;

class BinPackingTest extends BinPackingTestBase
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testAddBin()
    {
        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $result = $bin->setWidth(100);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setHeight(120);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setDepth(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setMaxWeight(10);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterWidth(110);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterHeight(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterDepth(140);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setWeight(0.1);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setIdentifier('Test');
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);

        $this->assertEquals('Test', $bin->getIdentifier());
        $this->assertEquals(100, $bin->getWidth());
        $this->assertEquals(120, $bin->getHeight());
        $this->assertEquals(130, $bin->getDepth());
    }

    public function testDuplicateItem()
    {
        $this->setExpectedException('\BinPacking3d\Exception\CriticalException');
        $request = new \BinPacking3d\Entity\Request();

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setWeight(5);
        $item->setItemIdentifier('Test');
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setWeight(5);
        $item->setItemIdentifier('Test');
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);
    }

    public function testInvalidItem()
    {
        $this->setExpectedException('\BinPacking3d\Exception\CriticalException');
        $request = new \BinPacking3d\Entity\Request();

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);
    }

    public function testAddInvalidBin()
    {
        $this->setExpectedException('\BinPacking3d\Exception\CriticalException');
        $request = new \BinPacking3d\Entity\Request();

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $result = $bin->setWidth(100);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setHeight(120);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setDepth(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setMaxWeight(10);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterWidth(110);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterHeight(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterDepth(140);
        $request->addBin($bin);
    }

    public function testAddDuplicateBin()
    {
        $this->setExpectedException('\BinPacking3d\Exception\CriticalException');
        $request = new \BinPacking3d\Entity\Request();

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $result = $bin->setWidth(100);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setHeight(120);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setDepth(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setMaxWeight(10);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterWidth(110);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterHeight(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterDepth(140);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setWeight(0.1);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setIdentifier('Test');
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $request->addBin($bin);

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $result = $bin->setWidth(100);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setHeight(120);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setDepth(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setMaxWeight(10);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterWidth(110);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterHeight(130);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setOuterDepth(140);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setWeight(0.1);
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $result = $bin->setIdentifier('Test');
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $result);
        $request->addBin($bin);
    }

    public function testRequestNoApiKey()
    {
        $request = new \BinPacking3d\Entity\Request();
        $this->setExpectedException('BinPacking3d\Exception\CriticalException');
        $request->validate();
    }

    public function testAddBinExceptionOuterWidth()
    {
        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $bin->setWidth(50);
        $bin->setOuterWidth(51);
        $this->assertEquals(50, $bin->getWidth());
        $this->assertEquals(51, $bin->getOuterWidth());
        $this->assertGreaterThan($bin->getWidth(), $bin->getOuterWidth());

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $this->setExpectedException('Exception');
        $bin->setOuterWidth(100);
    }

    // tests

    public function testAddBinExceptionOuterDepth()
    {
        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $bin->setDepth(50);
        $bin->setOuterDepth(51);
        $this->assertEquals(50, $bin->getDepth());
        $this->assertEquals(51, $bin->getOuterDepth());
        $this->assertGreaterThan($bin->getDepth(), $bin->getOuterDepth());

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $this->setExpectedException('Exception');
        $bin->setOuterDepth(100);
    }

    public function testAddBinExceptionOuterHeight()
    {
        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $bin->setHeight(50);
        $bin->setOuterHeight(51);
        $this->assertEquals(50, $bin->getHeight());
        $this->assertEquals(51, $bin->getOuterHeight());
        $this->assertGreaterThan($bin->getHeight(), $bin->getOuterHeight());

        $bin = new \BinPacking3d\Entity\Bin;
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $bin);
        $this->setExpectedException('Exception');
        $bin->setOuterHeight(100);
    }


}

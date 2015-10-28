<?php namespace BinPacking3d;

use BinPacking3d\Entity\Request;

/**
 * Class PackIntoMany
 * @package BinPacking3d
 */
class PackIntoMany extends Query
{

    private $defaults = [
        'images_background_color' => '255,255,255',
        'images_bin_border_color' => '59,59,59',
        'images_bin_fill_color' => '230,230,230',
        'images_item_border_color' => '214,79,79',
        'images_item_fill_color' => '177,14,14',
        'images_item_back_border_color' => '215,103,103',
        'images_sbs_last_item_fill_color' => '99,93,93',
        'images_sbs_last_item_border_color' => '145,133,133',
        'images_width' => 250,
        'images_height' => 250,
        'images_source' => 'base64',
        'images_sbs' => 1,
        'stats' => 1,
        'item_coordinates' => 1,
        'images_complete' => 1,
        'images_separated' => 1
    ];

    /**
     * @param Request $request
     * @param array $params
     */
    public function __construct(Request $request, $params = [])
    {
        $this->setEndpoint('packIntoMany');
        $this->setRequest($request);
        $this->setParams(array_merge($this->defaults, $params));

        parent::__construct();
    }

}

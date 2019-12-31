<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode\Object;

/**
 * Class for generate Ean2 barcode
 */
class Ean2 extends Ean5
{
    protected $parities = [
        0 => ['A','A'],
        1 => ['A','B'],
        2 => ['B','A'],
        3 => ['B','B']
    ];

    /**
     * Default options for Ean2 barcode
     * @return void
     */
    protected function getDefaultOptions()
    {
        $this->barcodeLength = 2;
    }

    protected function getParity($i)
    {
        $modulo = $this->getText() % 4;
        return $this->parities[$modulo][$i];
    }
}

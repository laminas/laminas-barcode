<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode\Object;

/**
 * Class for generate Ean2 barcode
 *
 * @category   Laminas
 * @package    Laminas_Barcode
 */
class Ean2 extends Ean5
{

    protected $parities = array(
        0 => array('A','A'),
        1 => array('A','B'),
        2 => array('B','A'),
        3 => array('B','B')
    );

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

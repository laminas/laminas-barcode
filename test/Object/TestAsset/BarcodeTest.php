<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Object\TestAsset;

/**
 * @category   Laminas
 * @package    Laminas_Barcode
 * @subpackage UnitTests
 * @group      Laminas_Barcode
 */
class BarcodeTest extends \Laminas\Barcode\Object\AbstractObject
{

    protected function calculateBarcodeWidth()
    {
        return 1;
    }

    public function validateText($value)
    {}

    protected function prepareBarcode()
    {
        return array();
    }

    protected function checkSpecificParams()
    {}

    public function addTestInstruction(array $instruction)
    {
        $this->addInstruction($instruction);
    }

    public function addTestPolygon(array $points, $color = null, $filled = true)
    {
        $this->addPolygon($points, $color, $filled);
    }

    public function addTestText($text, $size, $position, $font, $color, $alignment = 'center', $orientation = 0)
    {
        $this->addText($text, $size, $position, $font, $color, $alignment, $orientation);
    }
}

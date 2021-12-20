<?php

namespace LaminasTest\Barcode\Object\TestAsset;

/**
 * @group      Laminas_Barcode
 */
class BarcodeTest extends \Laminas\Barcode\Object\AbstractObject
{

    protected function calculateBarcodeWidth()
    {
        return 1;
    }

    public function validateText($value)
    {
    }

    protected function prepareBarcode()
    {
        return [];
    }

    protected function checkSpecificParams()
    {
    }

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

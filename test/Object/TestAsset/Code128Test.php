<?php

namespace LaminasTest\Barcode\Object\TestAsset;

/**
 * @group      Laminas_Barcode
 */
class Code128Test extends \Laminas\Barcode\Object\Code128
{
    public function convertToBarcodeChars($string)
    {
        return parent::convertToBarcodeChars($string);
    }
}

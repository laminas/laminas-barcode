<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

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

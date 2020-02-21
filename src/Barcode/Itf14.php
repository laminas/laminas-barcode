<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode\Barcode;

use Laminas\Barcode\Object\Code25interleaved as ObjectCode25interleaved;

/**
 * Class for generate Itf14 barcode
 */
class Itf14 extends ObjectCode25interleaved
{
    /**
     * Default options for Identcode barcode
     * @return void
     */
    protected function getDefaultOptions()
    {
        $this->barcodeLength = 14;
        $this->mandatoryChecksum = true;
    }
}

<?php

namespace LaminasTest\Barcode\Object\TestAsset;

class BarcodeNamespace extends \Laminas\Barcode\Object\Error
{
    public function getType()
    {
        return $this->type;
    }
}

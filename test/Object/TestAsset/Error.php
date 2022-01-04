<?php

namespace LaminasTest\Barcode\Object\TestAsset;

class Error extends \Laminas\Barcode\Object\Error
{
    public function getType()
    {
        return $this->type;
    }
}

<?php

namespace LaminasTest\Barcode\Renderer\TestAsset;

class RendererNamespace extends \Laminas\Barcode\Renderer\Image
{
    public function getType()
    {
        return $this->type;
    }
}

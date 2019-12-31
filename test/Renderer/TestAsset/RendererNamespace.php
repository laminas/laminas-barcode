<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Renderer\TestAsset;

class RendererNamespace extends \Laminas\Barcode\Renderer\Image
{
    public function getType()
    {
        return $this->type;
    }
}

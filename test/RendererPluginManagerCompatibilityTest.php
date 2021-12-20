<?php

namespace LaminasTest\Barcode;

use Laminas\Barcode\Exception\InvalidArgumentException;
use Laminas\Barcode\Renderer\AbstractRenderer;
use Laminas\Barcode\RendererPluginManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use PHPUnit\Framework\TestCase;

class RendererPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager(): RendererPluginManager
    {
        return new RendererPluginManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException(): string
    {
        return InvalidArgumentException::class;
    }

    protected function getInstanceOf(): string
    {
        return AbstractRenderer::class;
    }
}

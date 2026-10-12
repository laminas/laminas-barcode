<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use Laminas\Barcode\Renderer\AbstractRenderer;
use Laminas\Barcode\RendererPluginManager;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class RendererPluginManagerCompatibilityTest extends TestCase
{
    use PluginManagerCompatibilityTrait;

    protected static function getPluginManager(): RendererPluginManager
    {
        return new RendererPluginManager(new ServiceManager());
    }

    protected function getInstanceOf(): string
    {
        return AbstractRenderer::class;
    }
}

<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use Laminas\Barcode\Object\AbstractObject;
use Laminas\Barcode\ObjectPluginManager;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class ObjectPluginManagerCompatibilityTest extends TestCase
{
    use PluginManagerCompatibilityTrait;

    protected static function getPluginManager(): ObjectPluginManager
    {
        return new ObjectPluginManager(new ServiceManager());
    }

    protected function getInstanceOf(): string
    {
        return AbstractObject::class;
    }
}

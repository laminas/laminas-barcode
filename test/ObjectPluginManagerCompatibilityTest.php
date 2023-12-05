<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use Laminas\Barcode\Exception\InvalidArgumentException;
use Laminas\Barcode\Object\AbstractObject;
use Laminas\Barcode\ObjectPluginManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use PHPUnit\Framework\TestCase;

class ObjectPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    /**
     * @return ObjectPluginManager
     */
    protected static function getPluginManager()
    {
        return new ObjectPluginManager(new ServiceManager());
    }

    /**
     * @return string
     */
    protected function getV2InvalidPluginException()
    {
        return InvalidArgumentException::class;
    }

    /**
     * @return string
     */
    protected function getInstanceOf()
    {
        return AbstractObject::class;
    }
}

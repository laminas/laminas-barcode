<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use GdImage;
use PHPUnit\Framework\Assert;

trait AssertIsGdImageTrait
{
    /**
     * @param mixed $value
     */
    public static function assertIsGdImage($value, string $message = ''): void
    {
        $message = $message ?: sprintf(
            'Failed asserting that %s is a GD image',
            is_object($value) ? get_class($value) : gettype($value)
        );

        if (PHP_MAJOR_VERSION === 8) {
            Assert::assertInstanceOf(GdImage::class, $value);
            return;
        }

        if (! is_resource($value) || get_resource_type($value) !== 'gd') {
            Assert::fail($message);
        }

        Assert::assertTrue(true);
    }
}

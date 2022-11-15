<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use GdImage;
use PHPUnit\Framework\Assert;

use function get_debug_type;
use function get_resource_type;
use function is_resource;
use function sprintf;

use const PHP_MAJOR_VERSION;

trait AssertIsGdImageTrait
{
    /**
     * @param mixed $value
     */
    public static function assertIsGdImage($value, string $message = ''): void
    {
        $message = $message ?: sprintf(
            'Failed asserting that %s is a GD image',
            get_debug_type($value)
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

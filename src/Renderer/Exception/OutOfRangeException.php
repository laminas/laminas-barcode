<?php

namespace Laminas\Barcode\Renderer\Exception;

use Laminas\Barcode\Exception;

/**
 * Exception for Laminas\Barcode component.
 */
class OutOfRangeException extends Exception\OutOfRangeException implements
    ExceptionInterface
{
}

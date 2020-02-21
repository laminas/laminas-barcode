<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode;

/**
 * Plugin manager implementation for barcode parsers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Object\AbstractObject. Additionally, it registers a number of default
 * barcode parsers.
 *
 * @deprecated Deprecated in 2.8 and will be removed in 3.0. Use Laminas\Barcode\BarcodePluginManager instead.
 */
class ObjectPluginManager extends BarcodePluginManager
{
}

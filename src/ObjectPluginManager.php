<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode;

use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Plugin manager implementation for barcode parsers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Object\AbstractObject. Additionally, it registers a number of default
 * barcode parsers.
 *
 * @category   Laminas
 * @package    Laminas_Barcode
 */
class ObjectPluginManager extends AbstractPluginManager
{
    /**
     * Default set of barcode parsers
     *
     * @var array
     */
    protected $invokableClasses = array(
        'codabar'           => 'Laminas\Barcode\Object\Codabar',
        'code128'           => 'Laminas\Barcode\Object\Code128',
        'code25'            => 'Laminas\Barcode\Object\Code25',
        'code25interleaved' => 'Laminas\Barcode\Object\Code25interleaved',
        'code39'            => 'Laminas\Barcode\Object\Code39',
        'ean13'             => 'Laminas\Barcode\Object\Ean13',
        'ean2'              => 'Laminas\Barcode\Object\Ean2',
        'ean5'              => 'Laminas\Barcode\Object\Ean5',
        'ean8'              => 'Laminas\Barcode\Object\Ean8',
        'error'             => 'Laminas\Barcode\Object\Error',
        'identcode'         => 'Laminas\Barcode\Object\Identcode',
        'itf14'             => 'Laminas\Barcode\Object\Itf14',
        'leitcode'          => 'Laminas\Barcode\Object\Leitcode',
        'planet'            => 'Laminas\Barcode\Object\Planet',
        'postnet'           => 'Laminas\Barcode\Object\Postnet',
        'royalmail'         => 'Laminas\Barcode\Object\Royalmail',
        'upca'              => 'Laminas\Barcode\Object\Upca',
        'upce'              => 'Laminas\Barcode\Object\Upce',
    );

    /**
     * Validate the plugin
     *
     * Checks that the barcode parser loaded is an instance
     * of Object\AbstractObject.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\InvalidArgumentException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Object\AbstractObject) {
            // we're okay
            return;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must extend %s\Object\AbstractObject',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}

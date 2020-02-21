<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * Plugin manager implementation for barcode parsers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Barcode\AbstractBarcode. Additionally, it registers a number of default
 * barcode parsers.
 */
class BarcodePluginManager extends AbstractPluginManager
{
    /**
     * @var bool Ensure services are not shared (v2 property)
     */
    protected $shareByDefault = false;

    /**
     * @var bool Ensure services are not shared (v3 property)
     */
    protected $sharedByDefault = false;

    /**
     * Default set of symmetric adapters
     *
     * @var array
     */
    protected $aliases = [
        'codabar'           => Barcode\Codabar::class,
        'code128'           => Barcode\Code128::class,
        'code25'            => Barcode\Code25::class,
        'code25interleaved' => Barcode\Code25interleaved::class,
        'code39'            => Barcode\Code39::class,
        'ean13'             => Barcode\Ean13::class,
        'ean2'              => Barcode\Ean2::class,
        'ean5'              => Barcode\Ean5::class,
        'ean8'              => Barcode\Ean8::class,
        'error'             => Barcode\Error::class,
        'identcode'         => Barcode\Identcode::class,
        'itf14'             => Barcode\Itf14::class,
        'leitcode'          => Barcode\Leitcode::class,
        'planet'            => Barcode\Planet::class,
        'postnet'           => Barcode\Postnet::class,
        'royalmail'         => Barcode\Royalmail::class,
        'upca'              => Barcode\Upca::class,
        'upce'              => Barcode\Upce::class,

        // Legacy Zend Framework aliases
        \Zend\Barcode\Object\Codabar::class => Object\Codabar::class,
        \Zend\Barcode\Object\Code128::class => Object\Code128::class,
        \Zend\Barcode\Object\Code25::class => Object\Code25::class,
        \Zend\Barcode\Object\Code25interleaved::class => Object\Code25interleaved::class,
        \Zend\Barcode\Object\Code39::class => Object\Code39::class,
        \Zend\Barcode\Object\Ean13::class => Object\Ean13::class,
        \Zend\Barcode\Object\Ean2::class => Object\Ean2::class,
        \Zend\Barcode\Object\Ean5::class => Object\Ean5::class,
        \Zend\Barcode\Object\Ean8::class => Object\Ean8::class,
        \Zend\Barcode\Object\Error::class => Object\Error::class,
        \Zend\Barcode\Object\Identcode::class => Object\Identcode::class,
        \Zend\Barcode\Object\Itf14::class => Object\Itf14::class,
        \Zend\Barcode\Object\Leitcode::class => Object\Leitcode::class,
        \Zend\Barcode\Object\Planet::class => Object\Planet::class,
        \Zend\Barcode\Object\Postnet::class => Object\Postnet::class,
        \Zend\Barcode\Object\Royalmail::class => Object\Royalmail::class,
        \Zend\Barcode\Object\Upca::class => Object\Upca::class,
        \Zend\Barcode\Object\Upce::class => Object\Upce::class,

        // v2 normalized FQCNs
        'zendbarcodeobjectcodabar' => Barcode\Codabar::class,
        'zendbarcodeobjectcode128' => Barcode\Code128::class,
        'zendbarcodeobjectcode25' => Barcode\Code25::class,
        'zendbarcodeobjectcode25interleaved' => Barcode\Code25interleaved::class,
        'zendbarcodeobjectcode39' => Barcode\Code39::class,
        'zendbarcodeobjectean13' => Barcode\Ean13::class,
        'zendbarcodeobjectean2' => Barcode\Ean2::class,
        'zendbarcodeobjectean5' => Barcode\Ean5::class,
        'zendbarcodeobjectean8' => Barcode\Ean8::class,
        'zendbarcodeobjecterror' => Barcode\Error::class,
        'zendbarcodeobjectidentcode' => Barcode\Identcode::class,
        'zendbarcodeobjectitf14' => Barcode\Itf14::class,
        'zendbarcodeobjectleitcode' => Barcode\Leitcode::class,
        'zendbarcodeobjectplanet' => Barcode\Planet::class,
        'zendbarcodeobjectpostnet' => Barcode\Postnet::class,
        'zendbarcodeobjectroyalmail' => Barcode\Royalmail::class,
        'zendbarcodeobjectupca' => Barcode\Upca::class,
        'zendbarcodeobjectupce' => Barcode\Upce::class,
    ];

    protected $factories = [
        Barcode\Codabar::class           => InvokableFactory::class,
        Barcode\Code128::class           => InvokableFactory::class,
        Barcode\Code25::class            => InvokableFactory::class,
        Barcode\Code25interleaved::class => InvokableFactory::class,
        Barcode\Code39::class            => InvokableFactory::class,
        Barcode\Ean13::class             => InvokableFactory::class,
        Barcode\Ean2::class              => InvokableFactory::class,
        Barcode\Ean5::class              => InvokableFactory::class,
        Barcode\Ean8::class              => InvokableFactory::class,
        Barcode\Error::class             => InvokableFactory::class,
        Barcode\Identcode::class         => InvokableFactory::class,
        Barcode\Itf14::class             => InvokableFactory::class,
        Barcode\Leitcode::class          => InvokableFactory::class,
        Barcode\Planet::class            => InvokableFactory::class,
        Barcode\Postnet::class           => InvokableFactory::class,
        Barcode\Royalmail::class         => InvokableFactory::class,
        Barcode\Upca::class              => InvokableFactory::class,
        Barcode\Upce::class              => InvokableFactory::class,

        // v2 canonical FQCNs

        'laminasbarcodeobjectcodabar'           => InvokableFactory::class,
        'laminasbarcodeobjectcode128'           => InvokableFactory::class,
        'laminasbarcodeobjectcode25'            => InvokableFactory::class,
        'laminasbarcodeobjectcode25interleaved' => InvokableFactory::class,
        'laminasbarcodeobjectcode39'            => InvokableFactory::class,
        'laminasbarcodeobjectean13'             => InvokableFactory::class,
        'laminasbarcodeobjectean2'              => InvokableFactory::class,
        'laminasbarcodeobjectean5'              => InvokableFactory::class,
        'laminasbarcodeobjectean8'              => InvokableFactory::class,
        'laminasbarcodeobjecterror'             => InvokableFactory::class,
        'laminasbarcodeobjectidentcode'         => InvokableFactory::class,
        'laminasbarcodeobjectitf14'             => InvokableFactory::class,
        'laminasbarcodeobjectleitcode'          => InvokableFactory::class,
        'laminasbarcodeobjectplanet'            => InvokableFactory::class,
        'laminasbarcodeobjectpostnet'           => InvokableFactory::class,
        'laminasbarcodeobjectroyalmail'         => InvokableFactory::class,
        'laminasbarcodeobjectupca'              => InvokableFactory::class,
        'laminasbarcodeobjectupce'              => InvokableFactory::class,
    ];

    protected $instanceOf = Barcode\AbstractBarcode::class;

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Validates against `$instanceOf`.
     *
     * @param mixed $plugin
     * @throws InvalidServiceException
     */
    public function validate($plugin)
    {
        if (! $plugin instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                get_class($this),
                $this->instanceOf,
                (is_object($plugin) ? get_class($plugin) : gettype($plugin))
            ));
        }
    }

    /**
     * Validate the plugin is of the expected type (v2).
     *
     * Proxies to `validate()`.
     *
     * @param mixed $plugin
     * @throws Exception\InvalidArgumentException
     */
    public function validatePlugin($plugin)
    {
        try {
            $this->validate($plugin);
        } catch (InvalidServiceException $e) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Plugin of type %s is invalid; must extend %s',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                Barcode\AbstractBarcode::class
            ), $e->getCode(), $e);
        }
    }
}

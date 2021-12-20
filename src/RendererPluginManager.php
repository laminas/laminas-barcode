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
 * Plugin manager implementation for barcode renderers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Renderer\AbstractRenderer. Additionally, it registers a number of default
 * barcode renderers.
 */
class RendererPluginManager extends AbstractPluginManager
{
    /**
     * @var bool Ensure services are not shared (v2)
     */
    protected $shareByDefault = false;

    /**
     * @var bool Ensure services are not shared (v3)
     */
    protected $sharedByDefault = false;

    /**
     * Default set of barcode renderers
     *
     * @var array
     */
    protected $aliases = [
        'image' => Renderer\Image::class,
        'pdf'   => Renderer\Pdf::class,
        'svg'   => Renderer\Svg::class,

        // Legacy Zend Framework aliases
        \Zend\Barcode\Renderer\Image::class => Renderer\Image::class,
        \Zend\Barcode\Renderer\Pdf::class => Renderer\Pdf::class,
        \Zend\Barcode\Renderer\Svg::class => Renderer\Svg::class,

        // v2 normalized FQCNs
        'zendbarcoderendererimage' => Renderer\Image::class,
        'zendbarcoderendererpdf' => Renderer\Pdf::class,
        'zendbarcoderenderersvg' => Renderer\Svg::class,
    ];

    protected $factories = [
        Renderer\Image::class => InvokableFactory::class,
        Renderer\Pdf::class   => InvokableFactory::class,
        Renderer\Svg::class   => InvokableFactory::class,

        // v2 canonical FQCNs

        'laminasbarcoderendererimage' => InvokableFactory::class,
        'laminasbarcoderendererpdf'   => InvokableFactory::class,
        'laminasbarcoderenderersvg'   => InvokableFactory::class,
    ];

    protected $instanceOf = Renderer\AbstractRenderer::class;

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
                Renderer\AbstractRenderer::class
            ), $e->getCode(), $e);
        }
    }
}

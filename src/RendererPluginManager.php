<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Barcode;

use Laminas\ServiceManager\AbstractPluginManager;

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
     * @var bool Ensure services are not shared
     */
    protected $shareByDefault = false;

    /**
     * Default set of barcode renderers
     *
     * @var array
     */
    protected $invokableClasses = [
        'image' => 'Laminas\Barcode\Renderer\Image',
        'pdf'   => 'Laminas\Barcode\Renderer\Pdf',
        'svg'   => 'Laminas\Barcode\Renderer\Svg'
    ];

    /**
     * Validate the plugin
     *
     * Checks that the barcode parser loaded is an instance
     * of Renderer\AbstractRenderer.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\InvalidArgumentException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Renderer\AbstractRenderer) {
            // we're okay
            return;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must extend %s\Renderer\AbstractRenderer',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}

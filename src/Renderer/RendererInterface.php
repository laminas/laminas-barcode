<?php

declare(strict_types=1);

namespace Laminas\Barcode\Renderer;

use Laminas\Barcode\Object\ObjectInterface;
use Traversable;

/**
 * Class for rendering the barcode
 */
interface RendererInterface
{
    /**
     * Constructor
     *
     * @param array|Traversable $options
     */
    public function __construct($options = null);

    /**
     * Set renderer state from options array
     *
     * @param  array $options
     * @return self Provides a fluent interface
     */
    public function setOptions($options);

    /**
     * Set renderer namespace for autoloading
     *
     * @param string $namespace
     * @return self Provides a fluent interface
     */
    public function setRendererNamespace($namespace);

    /**
     * Retrieve renderer namespace
     *
     * @return string
     */
    public function getRendererNamespace();

    /**
     * Retrieve renderer type
     *
     * @return string
     */
    public function getType();

    /**
     * Manually adjust top position
     *
     * @param int $value
     * @return self Provides a fluent interface
     */
    public function setTopOffset($value);

    /**
     * Retrieve vertical adjustment
     *
     * @return int
     */
    public function getTopOffset();

    /**
     * Manually adjust left position
     *
     * @param int $value
     * @return self Provides a fluent interface
     */
    public function setLeftOffset($value);

    /**
     * Retrieve vertical adjustment
     *
     * @return int
     */
    public function getLeftOffset();

    /**
     * Activate/Deactivate the automatic rendering of exception
     *
     * @param  bool $value
     * @return self
     */
    public function setAutomaticRenderError($value);

    /**
     * Horizontal position of the barcode in the rendering resource
     *
     * @param string $value
     * @return self Provides a fluent interface
     */
    public function setHorizontalPosition($value);

    /**
     * Horizontal position of the barcode in the rendering resource
     *
     * @return string
     */
    public function getHorizontalPosition();

    /**
     * Vertical position of the barcode in the rendering resource
     *
     * @param string $value
     * @return self Provides a fluent interface
     */
    public function setVerticalPosition($value);

    /**
     * Vertical position of the barcode in the rendering resource
     *
     * @return string
     */
    public function getVerticalPosition();

    /**
     * Set the size of a module
     *
     * @param float $value
     * @return self Provides a fluent interface
     */
    public function setModuleSize($value);

    /**
     * Set the size of a module
     *
     * @return float
     */
    public function getModuleSize();

    /**
     * Retrieve the automatic rendering of exception
     *
     * @return bool
     */
    public function getAutomaticRenderError();

    /**
     * Set the barcode object
     *
     * @return self Provides a fluent interface
     */
    public function setBarcode(ObjectInterface $barcode);

    /**
     * Retrieve the barcode object
     *
     * @return ObjectInterface
     */
    public function getBarcode();

    /**
     * Checking of parameters after all settings
     *
     * @return bool
     */
    public function checkParams();

    /**
     * Draw the barcode in the rendering resource
     *
     * @return mixed
     */
    public function draw();

    /**
     * Render the resource by sending headers and drawed resource
     *
     * @return mixed
     */
    public function render();
}

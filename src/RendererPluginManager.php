<?php

declare(strict_types=1);

namespace Laminas\Barcode;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Container\ContainerInterface;

use function class_exists;
use function get_debug_type;
use function sprintf;

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
     * Default set of barcode renderers
     */
    private const ALIASES = [
        'image' => Renderer\Image::class,
        'pdf'   => Renderer\Pdf::class,
        'svg'   => Renderer\Svg::class,

        // Legacy Zend Framework aliases
        '\Zend\Barcode\Renderer\Image' => Renderer\Image::class,
        '\Zend\Barcode\Renderer\Pdf'   => Renderer\Pdf::class,
        '\Zend\Barcode\Renderer\Svg'   => Renderer\Svg::class,

        // v2 normalized FQCNs
        'zendbarcoderendererimage' => Renderer\Image::class,
        'zendbarcoderendererpdf'   => Renderer\Pdf::class,
        'zendbarcoderenderersvg'   => Renderer\Svg::class,
    ];

    /**
     * Default set of barcode renderer factories
     */
    private const FACTORIES = [
        Renderer\Image::class => InvokableFactory::class,
        Renderer\Pdf::class   => InvokableFactory::class,
        Renderer\Svg::class   => InvokableFactory::class,

        // v2 canonical FQCNs
        'laminasbarcoderendererimage' => InvokableFactory::class,
        'laminasbarcoderendererpdf'   => InvokableFactory::class,
        'laminasbarcoderenderersvg'   => InvokableFactory::class,
    ];

    /** @var bool Ensure services are not shared (v2) */
    protected $shareByDefault = false;

    /**
     * {@inheritDoc}
     */
    protected $instanceOf = Renderer\AbstractRenderer::class;

    /**
     * The default aliases, factories and sharing flag are assigned to the
     * inherited properties instead of being redeclared, as those properties
     * are untyped in laminas-servicemanager v3 and typed in v4.
     *
     * @param null|ContainerInterface $configInstanceOrParentLocator
     * @param array<string, mixed> $config
     */
    public function __construct($configInstanceOrParentLocator = null, array $config = [])
    {
        $this->aliases         = $this->aliases + self::ALIASES;
        $this->factories       = $this->factories + self::FACTORIES;
        $this->sharedByDefault = false;

        parent::__construct($configInstanceOrParentLocator, $config);
    }

    /**
     * Retrieve a barcode renderer, optionally created with the given options.
     *
     * laminas-servicemanager v4 removed the `$options` argument from `get()`;
     * it is supported here so that options are honoured with v3 and v4.
     *
     * @param string $name
     * @param null|array<string, mixed> $options
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function get($name, ?array $options = null): mixed
    {
        if ($options === null || $options === []) {
            return parent::get($name);
        }

        if (! $this->has($name)) {
            if (! $this->autoAddInvokableClass || ! class_exists($name)) {
                throw new ServiceNotFoundException(sprintf(
                    'A plugin by the name "%s" was not found in the plugin manager %s',
                    $name,
                    static::class
                ));
            }

            $this->setFactory($name, InvokableFactory::class);
        }

        $instance = $this->build($name, $options);
        $this->validate($instance);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(mixed $plugin): void
    {
        if (! $plugin instanceof $this->instanceOf) {
            throw new InvalidServiceException(
                sprintf(
                    '%s can only create instances of %s; %s is invalid',
                    static::class,
                    $this->instanceOf,
                    get_debug_type($plugin)
                )
            );
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
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Plugin of type %s is invalid; must extend %s',
                    get_debug_type($plugin),
                    Renderer\AbstractRenderer::class
                ),
                $e->getCode(),
                $e
            );
        }
    }
}

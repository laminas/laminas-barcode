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
 * Plugin manager implementation for barcode parsers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Object\AbstractObject. Additionally, it registers a number of default
 * barcode parsers.
 */
class ObjectPluginManager extends AbstractPluginManager
{
    /**
     * Default set of symmetric adapters
     */
    private const ALIASES = [
        'codabar'           => Object\Codabar::class,
        'code128'           => Object\Code128::class,
        'code25'            => Object\Code25::class,
        'code25interleaved' => Object\Code25interleaved::class,
        'code39'            => Object\Code39::class,
        'ean13'             => Object\Ean13::class,
        'ean2'              => Object\Ean2::class,
        'ean5'              => Object\Ean5::class,
        'ean8'              => Object\Ean8::class,
        'error'             => Object\Error::class,
        'identcode'         => Object\Identcode::class,
        'itf14'             => Object\Itf14::class,
        'leitcode'          => Object\Leitcode::class,
        'planet'            => Object\Planet::class,
        'postnet'           => Object\Postnet::class,
        'royalmail'         => Object\Royalmail::class,
        'upca'              => Object\Upca::class,
        'upce'              => Object\Upce::class,

        // Legacy Zend Framework aliases
        'Zend\Barcode\Object\Codabar'           => Object\Codabar::class,
        'Zend\Barcode\Object\Code128'           => Object\Code128::class,
        'Zend\Barcode\Object\Code25'            => Object\Code25::class,
        'Zend\Barcode\Object\Code25interleaved' => Object\Code25interleaved::class,
        'Zend\Barcode\Object\Code39'            => Object\Code39::class,
        'Zend\Barcode\Object\Ean13'             => Object\Ean13::class,
        'Zend\Barcode\Object\Ean2'              => Object\Ean2::class,
        'Zend\Barcode\Object\Ean5'              => Object\Ean5::class,
        'Zend\Barcode\Object\Ean8'              => Object\Ean8::class,
        'Zend\Barcode\Object\Error'             => Object\Error::class,
        'Zend\Barcode\Object\Identcode'         => Object\Identcode::class,
        'Zend\Barcode\Object\Itf14'             => Object\Itf14::class,
        'Zend\Barcode\Object\Leitcode'          => Object\Leitcode::class,
        'Zend\Barcode\Object\Planet'            => Object\Planet::class,
        'Zend\Barcode\Object\Postnet'           => Object\Postnet::class,
        'Zend\Barcode\Object\Royalmail'         => Object\Royalmail::class,
        'Zend\Barcode\Object\Upca'              => Object\Upca::class,
        'Zend\Barcode\Object\Upce'              => Object\Upce::class,

        // v2 normalized FQCNs
        'zendbarcodeobjectcodabar'           => Object\Codabar::class,
        'zendbarcodeobjectcode128'           => Object\Code128::class,
        'zendbarcodeobjectcode25'            => Object\Code25::class,
        'zendbarcodeobjectcode25interleaved' => Object\Code25interleaved::class,
        'zendbarcodeobjectcode39'            => Object\Code39::class,
        'zendbarcodeobjectean13'             => Object\Ean13::class,
        'zendbarcodeobjectean2'              => Object\Ean2::class,
        'zendbarcodeobjectean5'              => Object\Ean5::class,
        'zendbarcodeobjectean8'              => Object\Ean8::class,
        'zendbarcodeobjecterror'             => Object\Error::class,
        'zendbarcodeobjectidentcode'         => Object\Identcode::class,
        'zendbarcodeobjectitf14'             => Object\Itf14::class,
        'zendbarcodeobjectleitcode'          => Object\Leitcode::class,
        'zendbarcodeobjectplanet'            => Object\Planet::class,
        'zendbarcodeobjectpostnet'           => Object\Postnet::class,
        'zendbarcodeobjectroyalmail'         => Object\Royalmail::class,
        'zendbarcodeobjectupca'              => Object\Upca::class,
        'zendbarcodeobjectupce'              => Object\Upce::class,
    ];

    /**
     * Default set of barcode object factories
     */
    private const FACTORIES = [
        Object\Codabar::class           => InvokableFactory::class,
        Object\Code128::class           => InvokableFactory::class,
        Object\Code25::class            => InvokableFactory::class,
        Object\Code25interleaved::class => InvokableFactory::class,
        Object\Code39::class            => InvokableFactory::class,
        Object\Ean13::class             => InvokableFactory::class,
        Object\Ean2::class              => InvokableFactory::class,
        Object\Ean5::class              => InvokableFactory::class,
        Object\Ean8::class              => InvokableFactory::class,
        Object\Error::class             => InvokableFactory::class,
        Object\Identcode::class         => InvokableFactory::class,
        Object\Itf14::class             => InvokableFactory::class,
        Object\Leitcode::class          => InvokableFactory::class,
        Object\Planet::class            => InvokableFactory::class,
        Object\Postnet::class           => InvokableFactory::class,
        Object\Royalmail::class         => InvokableFactory::class,
        Object\Upca::class              => InvokableFactory::class,
        Object\Upce::class              => InvokableFactory::class,

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

    /** @var bool Ensure services are not shared (v2 property) */
    protected $shareByDefault = false;

    /** {@inheritDoc} */
    protected $instanceOf = Object\AbstractObject::class;

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
     * Retrieve a barcode object, optionally created with the given options.
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

    /** {@inheritDoc} */
    public function validate(mixed $plugin): void
    {
        if (! $plugin instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $this->instanceOf,
                get_debug_type($plugin)
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
                get_debug_type($plugin),
                Object\AbstractObject::class
            ), $e->getCode(), $e);
        }
    }
}

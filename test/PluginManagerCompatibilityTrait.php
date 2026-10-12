<?php

declare(strict_types=1);

namespace LaminasTest\Barcode;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionProperty;
use stdClass;

use function assert;
use function is_string;

/**
 * Plugin manager checks that run against laminas-servicemanager v3 and v4.
 *
 * Laminas\ServiceManager\Test\CommonPluginManagerTrait cannot be used, as its
 * abstract method signatures are not compatible between both major versions.
 */
trait PluginManagerCompatibilityTrait
{
    public function testInstanceOfMatches(): void
    {
        $manager    = self::getPluginManager();
        $reflection = new ReflectionProperty($manager, 'instanceOf');
        self::assertSame($this->getInstanceOf(), $reflection->getValue($manager), 'instanceOf does not match');
    }

    public function testRegisteringInvalidElementRaisesException(): void
    {
        $this->expectException(InvalidServiceException::class);
        self::getPluginManager()->setService('test', $this);
    }

    public function testLoadingInvalidElementRaisesException(): void
    {
        $manager = self::getPluginManager();
        $manager->setInvokableClass('test', stdClass::class);
        $this->expectException(InvalidServiceException::class);
        $manager->get('test');
    }

    #[DataProvider('aliasProvider')]
    public function testPluginAliasesResolveToNewInstances(string $alias, string $expected): void
    {
        $manager = self::getPluginManager();
        $plugin  = $manager->get($alias);

        self::assertInstanceOf($expected, $plugin, "Alias '$alias' does not resolve");
        self::assertNotSame($plugin, $manager->get($alias), "Alias '$alias' resolves to a shared instance");
    }

    /**
     * @return list<array{string, string}>
     */
    public static function aliasProvider(): array
    {
        $manager    = self::getPluginManager();
        $reflection = new ReflectionProperty($manager, 'aliases');
        $data       = [];

        foreach ($reflection->getValue($manager) as $alias => $expected) {
            assert(is_string($alias) && is_string($expected));
            $data[] = [$alias, $expected];
        }

        return $data;
    }

    abstract protected static function getPluginManager(): AbstractPluginManager;

    /**
     * @return class-string
     */
    abstract protected function getInstanceOf(): string;
}

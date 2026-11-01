<?php

declare(strict_types=1);

use App\DoctrineCollection\Plugin;
use PHPUnit\Framework\TestCase;
use Psalm\Plugin\PluginEntryPointInterface;

/**
 * @psalm-suppress UnusedClass
 *
 * @internal
 *
 * @coversNothing
 */
final class DependencySmokeTest extends TestCase
{
    public function testPsalmPluginApiIsAvailable(): void
    {
        self::assertTrue(
            interface_exists(PluginEntryPointInterface::class),
        );
    }

    public function testPluginImplementsEntryPointInterface(): void
    {
        self::assertTrue(
            is_subclass_of(Plugin::class, PluginEntryPointInterface::class),
        );
    }
}
<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psalm\Plugin\PluginEntryPointInterface;

/**
 * @psalm-suppress UnusedClass
 *
 * PHPUnit discovers this test class automatically.
 */
final class DependencySmokeTest extends TestCase
{
    public function testPsalmPluginApiIsAvailable(): void
    {
        $this->assertTrue(
            interface_exists(PluginEntryPointInterface::class),
        );
    }
}

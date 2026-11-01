<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psalm\Plugin\PluginEntryPointInterface;

final class DependencySmokeTest extends TestCase
{
    public function testPsalmPluginApiIsAvailable(): void
    {
        $this->assertTrue(
            interface_exists(PluginEntryPointInterface::class),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\DoctrineCollection;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

/**
 * @psalm-suppress UnusedClass
 */
final class Plugin implements PluginEntryPointInterface
{
    #[\Override]
    public function __invoke(
        RegistrationInterface $registration,
        ?\SimpleXMLElement $config = null,
    ): void {}
}

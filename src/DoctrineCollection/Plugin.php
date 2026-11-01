<?php

declare(strict_types=1);

namespace App\DoctrineCollection;

use App\DoctrineCollection\Hook\AfterClassLikeVisit;
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
    ): void {
        require_once __DIR__.'/Hook/AfterClassLikeVisit.php';

        $registration->registerHooksFromClass(AfterClassLikeVisit::class);
    }
}

<?php

declare(strict_types=1);

namespace App\DoctrineCollection\Hook;

use App\DoctrineCollection\Issue\MissingCollectionGeneric;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeVisitInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeVisitEvent;
use Psalm\Type\Atomic\TGenericObject;
use Psalm\Type\Atomic\TNamedObject;

/**
 * @psalm-suppress UnusedClass
 */
final class AfterClassLikeVisit implements AfterClassLikeVisitInterface
{
    private const array COLLECTION_CLASSES = [
        'Doctrine\Common\Collections\Collection',
        'Doctrine\Common\Collections\ArrayCollection',
    ];

    #[\Override]
    public static function afterClassLikeVisit(AfterClassLikeVisitEvent $event): void
    {
        $storage = $event->getStorage();

        foreach ($storage->methods as $method_storage) {
            $signature_return_type = $method_storage->signature_return_type;

            if (null === $signature_return_type) {
                continue;
            }

            $returns_collection = false;

            foreach ($signature_return_type->getAtomicTypes() as $atomic_type) {
                if (
                    $atomic_type instanceof TNamedObject
                    && \in_array($atomic_type->value, self::COLLECTION_CLASSES, true)
                ) {
                    $returns_collection = true;

                    break;
                }
            }

            if (!$returns_collection) {
                continue;
            }

            if (!$method_storage->has_docblock_return_type) {
                $location = $method_storage->signature_return_type_location;

                if (null !== $location) {
                    IssueBuffer::accepts(
                        new MissingCollectionGeneric(
                            'Collection-returning method must declare its generic types in PHPDoc.',
                            $location,
                        ),
                    );
                }

                continue;
            }

            $return_type = $method_storage->return_type;
            $location = $method_storage->return_type_location;

            if (null === $return_type || null === $location) {
                continue;
            }

            $collection_generic = null;

            foreach ($return_type->getAtomicTypes() as $atomic_type) {
                if (
                    $atomic_type instanceof TGenericObject
                    && \in_array($atomic_type->value, self::COLLECTION_CLASSES, true)
                ) {
                    $collection_generic = $atomic_type;

                    break;
                }
            }

            if (
                null === $collection_generic
                || 2 !== \count($collection_generic->type_params)
            ) {
                IssueBuffer::accepts(
                    new MissingCollectionGeneric(
                        'Collection PHPDoc must declare exactly two generic types: key and value.',
                        $location,
                    ),
                );
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace App\DoctrineCollection\Hook;

use App\DoctrineCollection\Issue\InvalidCollectionProperty;
use App\DoctrineCollection\Issue\MissingCollectionGeneric;
use App\DoctrineCollection\Issue\MissingCollectionPropertyGeneric;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeVisitInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeVisitEvent;
use Psalm\Type\Atomic\TGenericObject;
use Psalm\Type\Atomic\TLiteralClassString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Union;

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

        foreach ($storage->properties as $property_storage) {
            $target_entity = null;
            $is_collection_association = false;

            foreach ($property_storage->attributes as $attribute) {
                if (
                    !\in_array(
                        $attribute->fq_class_name,
                        [
                            'Doctrine\ORM\Mapping\OneToMany',
                            'Doctrine\ORM\Mapping\ManyToMany',
                        ],
                        true,
                    )
                ) {
                    continue;
                }

                $is_collection_association = true;

                foreach ($attribute->args as $argument) {
                    if ('targetEntity' !== $argument->name) {
                        continue;
                    }

                    if (!$argument->type instanceof Union) {
                        continue;
                    }

                    foreach ($argument->type->getAtomicTypes() as $argument_type) {
                        if ($argument_type instanceof TLiteralClassString) {
                            $target_entity = $argument_type->value;

                            break 2;
                        }
                    }
                }
            }

            if (!$is_collection_association) {
                continue;
            }

            $location = $property_storage->type_location
                ?? $property_storage->location;

            if (null === $location) {
                continue;
            }

            $property_type = $property_storage->type;

            if (null === $property_type) {
                IssueBuffer::accepts(
                    new MissingCollectionPropertyGeneric(
                        'Doctrine collection property must declare its generic types in PHPDoc.',
                        $location,
                    ),
                );

                continue;
            }

            $collection_generic = null;

            foreach ($property_type->getAtomicTypes() as $atomic_type) {
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
                    new MissingCollectionPropertyGeneric(
                        'Doctrine collection property must declare exactly two generic types: key and value.',
                        $location,
                    ),
                );

                continue;
            }

            if (null === $target_entity) {
                continue;
            }

            $value_type = $collection_generic->type_params[1];
            $matches_target_entity = false;

            foreach ($value_type->getAtomicTypes() as $value_atomic_type) {
                if (
                    $value_atomic_type instanceof TNamedObject
                    && 0 === strcasecmp($value_atomic_type->value, $target_entity)
                ) {
                    $matches_target_entity = true;

                    break;
                }
            }

            if (!$matches_target_entity) {
                IssueBuffer::accepts(
                    new InvalidCollectionProperty(
                        \sprintf(
                            'Doctrine collection property element type must be %s, matching targetEntity.',
                            $target_entity,
                        ),
                        $location,
                    ),
                );
            }
        }
    }
}

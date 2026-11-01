<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\DoctrineCollection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;

/**
 * @psalm-suppress UnusedClass
 */
final class MissingGeneric
{
    /**
     * @return Collection<int, string>
     */
    public function getOrders(): Collection
    {
        throw new \LogicException();
    }

    public function getItems(): Collection
    {
        throw new \LogicException();
    }

    /**
     * @return Collection<int>
     */
    public function getPosts(): Collection
    {
        throw new \LogicException();
    }

    /**
     * @return Collection<bool, string>
     */
    public function getInvalidKey(): Collection
    {
        throw new \LogicException();
    }

    /**
     * @return Collection<int, mixed>
     */
    public function getMixedValue(): Collection
    {
        throw new \LogicException();
    }

    /**
     * @return ArrayCollection<int, string>
     */
    public function getArrayCollection(): ArrayCollection
    {
        return new ArrayCollection();
    }

    public function getArrayCollectionWithoutGenerics(): ArrayCollection
    {
        return new ArrayCollection();
    }

    /**
     * @return Collection<int, string>&Selectable
     */
    public function getSelectableCollection(): Collection&Selectable
    {
        throw new \LogicException();
    }

    public function getSelectableCollectionWithoutGenerics(): Collection&Selectable
    {
        throw new \LogicException();
    }
}

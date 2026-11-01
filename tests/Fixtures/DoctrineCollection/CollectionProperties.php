<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\DoctrineCollection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Order {}

/**
 * @psalm-suppress UnusedClass
 */
#[ORM\Entity]
final class Customer
{
    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class)]
    public Collection $orders;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class)]
    public Collection $manyOrders;

    #[ORM\OneToMany(targetEntity: Order::class)]
    public Collection $missingGeneric;

    /**
     * @var Collection<int, Order>
     */
    public Collection $notAnAssociation;

    /**
     * @var ArrayCollection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class)]
    public ArrayCollection $arrayCollectionOrders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->manyOrders = new ArrayCollection();
        $this->missingGeneric = new ArrayCollection();
        $this->notAnAssociation = new ArrayCollection();
        $this->arrayCollectionOrders = new ArrayCollection();
    }
}

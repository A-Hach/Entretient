<?php

namespace App\Entity;

use App\Repository\OrderItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemsRepository::class)]
class OrderItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $Orders = null;

    #[ORM\ManyToOne (cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $Product = null;

    #[ORM\Column]
    private ?int $Quantity = null;

    #[ORM\Column]
    private ?float $UnitPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrders(): ?Orders
    {
        return $this->Orders;
    }

    public function setOrders(?Orders $Orders): static
    {
        $this->Orders = $Orders;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->Product;
    }

    public function setProduct(?Products $Product): static
    {
        $this->Product = $Product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->UnitPrice;
    }

    public function setUnitPrice(float $UnitPrice): static
    {
        $this->UnitPrice = $UnitPrice;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_user;


    /**
     * @ORM\Column(type="datetime")
     */
    private $DateOfOrder;

    /**
     * @ORM\Column(type="array")
     */
    private $Quantity = [];

    /**
     * @ORM\ManyToMany(targetEntity=Product::class)
     */
    private $HasProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Status;

    public function __construct()
    {
        $this->Id_product = new ArrayCollection();
        $this->HasProduct = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getIdProduct(): Collection
    {
        return $this->Id_product;
    }


    

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    

    public function getDateOfOrder(): ?\DateTimeInterface
    {
        return $this->DateOfOrder;
    }

    public function setDateOfOrder(\DateTimeInterface $DateOfOrder): self
    {
        $this->DateOfOrder = $DateOfOrder;

        return $this;
    }

    public function getQuantity(): ?array
    {
        return $this->Quantity;
    }

    public function setQuantity(array $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getHasProduct(): Collection
    {
        return $this->HasProduct;
    }

    public function addHasProduct(Product $hasProduct): self
    {
        if (!$this->HasProduct->contains($hasProduct)) {
            $this->HasProduct[] = $hasProduct;
        }

        return $this;
    }

    public function removeHasProduct(Product $hasProduct): self
    {
        if ($this->HasProduct->contains($hasProduct)) {
            $this->HasProduct->removeElement($hasProduct);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}

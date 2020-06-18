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
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="InOrder")
     */
    private $Id_product;

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

    public function __construct()
    {
        $this->Id_product = new ArrayCollection();
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

    public function addIdProduct(Product $idProduct): self
    {
        if (!$this->Id_product->contains($idProduct)) {
            $this->Id_product[] = $idProduct;
            $idProduct->setInOrder($this);
        }

        return $this;
    }

    public function removeIdProduct(Product $idProduct): self
    {
        if ($this->Id_product->contains($idProduct)) {
            $this->Id_product->removeElement($idProduct);
            // set the owning side to null (unless already changed)
            if ($idProduct->getInOrder() === $this) {
                $idProduct->setInOrder(null);
            }
        }

        return $this;
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
}

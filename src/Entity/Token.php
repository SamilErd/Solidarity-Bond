<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 */
class Token
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="tokenRegister")
     */
    private $id_user_register;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="tokenPassword")
     */
    private $id_user_password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUserRegister(): ?User
    {
        return $this->id_user_register;
    }

    public function setIdUserRegister(?User $id_user_register): self
    {
        $this->id_user_register = $id_user_register;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->Token;
    }

    public function setToken(string $Token): self
    {
        $this->Token = $Token;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getIdUserPassword(): ?User
    {
        return $this->id_user_password;
    }

    public function setIdUserPassword(?User $id_user_password): self
    {
        $this->id_user_password = $id_user_password;

        return $this;
    }
}

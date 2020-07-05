<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields={"Email"},
 *  message= " l'adresse email que vous avez rentrée est déja utilisée."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     */
    private $FirstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $LastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "Votre addresse email n'est pas valide."
     * )
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $phoneNum;

    /**
     * @ORM\Column(type="json")
     */
    private $Roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $Orders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Street;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PostalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Country;

    /**
     * @ORM\OneToOne(targetEntity=Token::class, mappedBy="id_user_register", cascade={"persist", "remove"})
     */
    private $tokenRegister;

    /**
     * @ORM\OneToOne(targetEntity=Token::class, mappedBy="id_user_password", cascade={"persist", "remove"})
     */
    private $tokenPassword;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="likes")
     */
    private $liked;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Comment::class, mappedBy="likes")
     */
    private $likes_comments;


    public function __construct()
    {
        $this->Orders = new ArrayCollection();
        $this->liked = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes_comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNum(): ?string
    {
        return $this->phoneNum;
    }

    public function setPhoneNum(string $phoneNum): self
    {
        $this->phoneNum = $phoneNum;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->Roles;
        //guarantee every user at least has ROLE_USER
        $roles[] = "ROLE_USER";

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->Roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->Orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->Orders->contains($order)) {
            $this->Orders[] = $order;
            $order->setIdUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->Orders->contains($order)) {
            $this->Orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getIdUser() === $this) {
                $order->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {

        return $this->Email;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getStreet(): ?string
    {
        return $this->Street;
    }

    public function setStreet(?string $Street): self
    {
        $this->Street = $Street;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->PostalCode;
    }

    public function setPostalCode(?int $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(?string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getTokenRegister(): ?Token
    {
        return $this->tokenRegister;
    }

    public function setTokenRegister(?Token $tokenRegister): self
    {
        $this->tokenRegister = $tokenRegister;

        // set (or unset) the owning side of the relation if necessary
        $newId_user = null === $tokenRegister ? null : $this;
        if ($tokenRegister->getIdUserRegister() !== $newId_user) {
            $tokenRegister->setIdUserRegister($newId_user);
        }

        return $this;
    }
    public function getTokenPassword(): ?Token
    {
        return $this->tokenPassword;
    }

    public function setTokenPassword(?Token $tokenPassword): self
    {
        $this->tokenPassword = $tokenPassword;

        // set (or unset) the owning side of the relation if necessary
        $newId_user = null === $tokenPassword ? null : $this;
        if ($tokenPassword->getIdUserPassword() !== $newId_user) {
            $tokenPassword->setIdUserPassword($newId_user);
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getLiked(): Collection
    {
        return $this->liked;
    }

    public function addLiked(Project $liked): self
    {
        if (!$this->liked->contains($liked)) {
            $this->liked[] = $liked;
            $liked->addLike($this);
        }

        return $this;
    }

    public function removeLiked(Project $liked): self
    {
        if ($this->liked->contains($liked)) {
            $this->liked->removeElement($liked);
            $liked->removeLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getIdUser() === $this) {
                $comment->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getLikesComments(): Collection
    {
        return $this->likes_comments;
    }

    public function addLikesComment(Comment $likesComment): self
    {
        if (!$this->likes_comments->contains($likesComment)) {
            $this->likes_comments[] = $likesComment;
            $likesComment->addLike($this);
        }

        return $this;
    }

    public function removeLikesComment(Comment $likesComment): self
    {
        if ($this->likes_comments->contains($likesComment)) {
            $this->likes_comments->removeElement($likesComment);
            $likesComment->removeLike($this);
        }

        return $this;
    }



}

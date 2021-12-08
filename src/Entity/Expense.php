<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExpenseRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    attributes: ["security" => "is_granted('ROLE_USER')"],
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_USER')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        "delete" =>  ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Expense
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read'])]
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['read'])]
    private $value;

    /**
     * @ORM\Column(type="date_immutable")
     */
    #[Groups(['read'])]
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="expense")
     */
    #[Groups(['read'])]
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="expenses")
     */
    #[Groups(['read'])]
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}

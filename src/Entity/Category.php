<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
#[ApiResource(
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
class Category
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
     * @ORM\OneToMany(targetEntity=Expense::class, mappedBy="category")
     */
    private $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

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

    /**
     * @return Collection|Expense[]
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
            $expense->setCategory($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getCategory() === $this) {
                $expense->setCategory(null);
            }
        }

        return $this;
    }
}

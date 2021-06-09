<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\IncomeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IncomeRepository::class)
 */
class Income
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Le champs {{ label }} doit être rempli pour définir son nom"
     * )
     * @Assert\Length(
     *     min = 10,
     *     max = 25,
     *     minMessage = "Vous avez entrer {{ value }} caractère, cependant le champs peut contenir à minimum {{ limit }}",
     *     maxMessage = "Vous avez entrer {{ value }} caractère, cependant le champs peut contenir au maximum {{ limit }}"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le champs {{ label }} doit être rempli pour définir un montant")
     * @Assert\PositiveOrZero
     * @Assert\Type(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $frequency;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="incomes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $budget;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="incomes")
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): ?self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}

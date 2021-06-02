<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BudgetRepository::class)
 */
class Budget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Income", mappedBy="budget")
     */
    private $incomes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->incomes = new ArrayCollection();
    }

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Income[]
     */
    public function getIncomes(): Collection
    {
        return $this->incomes;
    }

    /**
     * @param Income $income
     * @return Budget
     */
    public function addIncome(Income $income): self
    {
        if (!$this->incomes->contains($income)) {
            $this->incomes[] = $income;
            $income->setBudget($this);

        }

        return $this;
    }

    /**
     * @param Income $income
     * @return Budget
     */
    public function removeIncome(Income $income): self
    {
        if ($this->incomes->contains($income)) {
            $this->incomes->removeElement($income);

            if ($income->getBudget() === $this) {
                $income->setBudget(null);
            }
        }

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

}

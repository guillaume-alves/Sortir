<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="campus")
     */
    private $sortiesParCampus;

    public function __construct()
    {
        $this->sortiesParCampus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesParCampus(): Collection
    {
        return $this->sortiesParCampus;
    }

    public function addSortiesParCampus(Sortie $sortiesParCampus): self
    {
        if (!$this->sortiesParCampus->contains($sortiesParCampus)) {
            $this->sortiesParCampus[] = $sortiesParCampus;
            $sortiesParCampus->setCampus($this);
        }

        return $this;
    }

    public function removeSortiesParCampus(Sortie $sortiesParCampus): self
    {
        if ($this->sortiesParCampus->removeElement($sortiesParCampus)) {
            // set the owning side to null (unless already changed)
            if ($sortiesParCampus->getCampus() === $this) {
                $sortiesParCampus->setCampus(null);
            }
        }

        return $this;
    }
}

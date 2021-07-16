<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etat")
     */
    private $sortiesParEtat;

    public function __construct()
    {
        $this->sortiesParEtat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesParEtat(): Collection
    {
        return $this->sortiesParEtat;
    }

    public function addSortiesParEtat(Sortie $sortiesParEtat): self
    {
        if (!$this->sortiesParEtat->contains($sortiesParEtat)) {
            $this->sortiesParEtat[] = $sortiesParEtat;
            $sortiesParEtat->setEtat($this);
        }

        return $this;
    }

    public function removeSortiesParEtat(Sortie $sortiesParEtat): self
    {
        if ($this->sortiesParEtat->removeElement($sortiesParEtat)) {
            // set the owning side to null (unless already changed)
            if ($sortiesParEtat->getEtat() === $this) {
                $sortiesParEtat->setEtat(null);
            }
        }

        return $this;
    }

    // Pour le choice selector
    public function __toString() {
        return $this->getLibelle();
    }
}

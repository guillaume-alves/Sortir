<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
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
     * @ORM\Column(type="string", length=255)
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="villeLieux", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="lieu", cascade={"persist", "remove"})
     */
    private $sortiesParLieu;

    public function __construct()
    {
        $this->sortiesParLieu = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesParLieu(): Collection
    {
        return $this->sortiesParLieu;
    }

    public function addSortiesParLieu(Sortie $sortiesParLieu): self
    {
        if (!$this->sortiesParLieu->contains($sortiesParLieu)) {
            $this->sortiesParLieu[] = $sortiesParLieu;
            $sortiesParLieu->setLieu($this);
        }

        return $this;
    }

    public function removeSortiesParLieu(Sortie $sortiesParLieu): self
    {
        if ($this->sortiesParLieu->removeElement($sortiesParLieu)) {
            // set the owning side to null (unless already changed)
            if ($sortiesParLieu->getLieu() === $this) {
                $sortiesParLieu->setLieu(null);
            }
        }

        return $this;
    }
}

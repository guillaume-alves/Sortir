<?php


namespace App\Entity;


use Symfony\Component\Validator\Constraints\Date;

class CritereRechercheSorties
{
    /**
     * @var string | null
     */
    private $nomcampus;

    /**
     * @var string | null
     */
    private $mot;

    /**
     * @var date| null;
     */
    private $datedebut;

    /**
     * @var date| null;
     */
    private $datefin;

    /**
     * @var boolean;
     */
    private $jorganise;

    /**
     * @var boolean;
     */
    private $inscrit;

    /**
     * @var boolean;
     */
    private $noninscrit;

    /**
     * @var boolean;
     */
    private $sortiesold;

    /**
     * @return bool
     */
    public function isSortiesold(): bool
    {
        return $this->sortiesold;
    }

    /**
     * @param bool $sortiesold
     */
    public function setSortiesold(bool $sortiesold): void
    {
        $this->sortiesold = $sortiesold;
    }

    /**
     * @return bool
     */
    public function isNoninscrit(): bool
    {
        return $this->noninscrit;
    }

    /**
     * @param bool $noninscrit
     */
    public function setNoninscrit(bool $noninscrit): void
    {
        $this->noninscrit = $noninscrit;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool $inscrit
     */
    public function setInscrit(bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool
     */
    public function isJorganise(): bool
    {
        return $this->jorganise;
    }

    /**
     * @param bool $jorganise
     */
    public function setJorganise(bool $jorganise): void
    {
        $this->jorganise = $jorganise;
    }

    /**
     * @return date|null
     */
    public function getDatedebut(): ?date
    {
        return $this->datedebut;
    }

    /**
     * @param date|null $datedebut
     */
    public function setDatedebut(date $datedebut): void
    {
        $this->datedebut = $datedebut;
    }


    /**
     * @return date|null
     */
    public function getDatefin(): ?date
    {
        return $this->datefin;
    }

    /**
     * @param date|null $datefin
     */
    public function setDatefin(date $datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     * @return string|null
     */
    public function getNomcampus(): ?string
    {
        return $this->nomcampus;
    }

    /**
     * @param string|null $nomcampus
     */
    public function setNomcampus(string $nomcampus): void
    {
        $this->nomcampus = $nomcampus;
    }

    /**
     * @return string|null
     */
    public function getMot(): ?string
    {
        return $this->mot;
    }

    /**
     * @param string|null $mot
     */
    public function setMot(string $mot): void
    {
        $this->mot = $mot;
    }



}
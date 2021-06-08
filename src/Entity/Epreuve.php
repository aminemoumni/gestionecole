<?php

namespace App\Entity;

use App\Repository\EpreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpreuveRepository::class)
 */
class Epreuve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heureDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heureFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desingation;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="epreuve")
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity=classe::class, inversedBy="epreuves")
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="epreuves")
     */
    private $matiere;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heurDebut;
    }

    public function setHeureDebut(?\DateTimeInterface $heurDebut): self
    {
        $this->heurDebut = $heurDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heurFin;
    }

    public function setHeureFin(?\DateTimeInterface $heurFin): self
    {
        $this->heurFin = $heurFin;

        return $this;
    }

    public function getDesingation(): ?string
    {
        return $this->desingation;
    }

    public function setDesingation(?string $desingation): self
    {
        $this->desingation = $desingation;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setEpreuve($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEpreuve() === $this) {
                $note->setEpreuve(null);
            }
        }

        return $this;
    }

    public function getClasse(): ?classe
    {
        return $this->classe;
    }

    public function setClasse(?classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getMatiere(): ?Matieres
    {
        return $this->matiere;
    }

    public function setMatiere(?Matieres $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
}

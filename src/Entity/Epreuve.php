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
    private $heurDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heurFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desingation;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="epreuve")
     */
    private $notes;

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

    public function getHeurDebut(): ?\DateTimeInterface
    {
        return $this->heurDebut;
    }

    public function setHeurDebut(?\DateTimeInterface $heurDebut): self
    {
        $this->heurDebut = $heurDebut;

        return $this;
    }

    public function getHeurFin(): ?\DateTimeInterface
    {
        return $this->heurFin;
    }

    public function setHeurFin(?\DateTimeInterface $heurFin): self
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
}

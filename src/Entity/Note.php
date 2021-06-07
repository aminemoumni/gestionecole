<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Epreuve::class, inversedBy="notes")
     */
    private $epreuve;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="notes")
     */
    private $etudiant_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): self
    {
        $this->epreuve = $epreuve;

        return $this;
    }

    public function getEtudiantId(): ?Etudiant
    {
        return $this->etudiant_id;
    }

    public function setEtudiantId(?Etudiant $etudiant_id): self
    {
        $this->etudiant_id = $etudiant_id;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="class_id")
     */
    private $cours;

    /**
     * @ORM\OneToOne(targetEntity=Frais::class, mappedBy="class_id", cascade={"persist", "remove"})
     */
    private $frais;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="class_id")
     */
    private $inscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="class_id")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Epreuve::class, mappedBy="class_id")
     */
    private $epreuves;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->epreuves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setClassId($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getClassId() === $this) {
                $cour->setClassId(null);
            }
        }

        return $this;
    }

    public function getFrais(): ?Frais
    {
        return $this->frais;
    }

    public function setFrais(?Frais $frais): self
    {
        // unset the owning side of the relation if necessary
        if ($frais === null && $this->frais !== null) {
            $this->frais->setClassId(null);
        }

        // set the owning side of the relation if necessary
        if ($frais !== null && $frais->getClassId() !== $this) {
            $frais->setClassId($this);
        }

        $this->frais = $frais;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setClassId($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getClassId() === $this) {
                $inscription->setClassId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setClassId($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getClassId() === $this) {
                $etudiant->setClassId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Epreuve[]
     */
    public function getEpreuves(): Collection
    {
        return $this->epreuves;
    }

    public function addEpreufe(Epreuve $epreufe): self
    {
        if (!$this->epreuves->contains($epreufe)) {
            $this->epreuves[] = $epreufe;
            $epreufe->setClassId($this);
        }

        return $this;
    }

    public function removeEpreufe(Epreuve $epreufe): self
    {
        if ($this->epreuves->removeElement($epreufe)) {
            // set the owning side to null (unless already changed)
            if ($epreufe->getClassId() === $this) {
                $epreufe->setClassId(null);
            }
        }

        return $this;
    }
}

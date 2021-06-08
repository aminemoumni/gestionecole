<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNaiss;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomPere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenomPere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailPere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonctionPere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telPere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomMere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenomMere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailMere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonctionMere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telMere;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="inscriptions")
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inscription")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(?\DateTimeInterface $dateNaiss): self
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getNomPere(): ?string
    {
        return $this->nomPere;
    }

    public function setNomPere(?string $nomPere): self
    {
        $this->nomPere = $nomPere;

        return $this;
    }

    public function getPrenomPere(): ?string
    {
        return $this->prenomPere;
    }

    public function setPrenomPere(?string $prenomPere): self
    {
        $this->prenomPere = $prenomPere;

        return $this;
    }

    public function getEmailPere(): ?string
    {
        return $this->emailPere;
    }

    public function setEmailPere(?string $emailPere): self
    {
        $this->emailPere = $emailPere;

        return $this;
    }

    public function getFonctionPere(): ?string
    {
        return $this->fonctionPere;
    }

    public function setFonctionPere(?string $fonctionPere): self
    {
        $this->fonctionPere = $fonctionPere;

        return $this;
    }

    public function getTelPere(): ?string
    {
        return $this->telPere;
    }

    public function setTelPere(?string $telPere): self
    {
        $this->telPere = $telPere;

        return $this;
    }

    public function getNomMere(): ?string
    {
        return $this->nomMere;
    }

    public function setNomMere(?string $nomMere): self
    {
        $this->nomMere = $nomMere;

        return $this;
    }

    public function getPrenomMere(): ?string
    {
        return $this->prenomMere;
    }

    public function setPrenomMere(?string $prenomMere): self
    {
        $this->prenomMere = $prenomMere;

        return $this;
    }

    public function getEmailMere(): ?string
    {
        return $this->emailMere;
    }

    public function setEmailMere(?string $emailMere): self
    {
        $this->emailMere = $emailMere;

        return $this;
    }

    public function getFonctionMere(): ?string
    {
        return $this->fonctionMer;
    }

    public function setFonctionMere(?string $fonctionMer): self
    {
        $this->fonctionMer = $fonctionMer;

        return $this;
    }

    public function getTelMere(): ?string
    {
        return $this->telMere;
    }

    public function setTelMere(?string $telMere): self
    {
        $this->telMere = $telMere;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

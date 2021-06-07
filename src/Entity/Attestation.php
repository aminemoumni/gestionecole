<?php

namespace App\Entity;

use App\Repository\AttestationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttestationRepository::class)
 */
class Attestation
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
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity=etudiant::class, inversedBy="attestations")
     */
    private $etudiant_id;

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getEtudiantId(): ?etudiant
    {
        return $this->etudiant_id;
    }

    public function setEtudiantId(?etudiant $etudiant_id): self
    {
        $this->etudiant_id = $etudiant_id;

        return $this;
    }
}

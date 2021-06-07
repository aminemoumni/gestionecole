<?php

namespace App\Entity;

use App\Repository\FraisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FraisRepository::class)
 */
class Frais
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
    private $prix;

    /**
     * @ORM\OneToOne(targetEntity=classe::class, inversedBy="frais", cascade={"persist", "remove"})
     */
    private $class_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getClassId(): ?classe
    {
        return $this->class_id;
    }

    public function setClassId(?classe $class_id): self
    {
        $this->class_id = $class_id;

        return $this;
    }
}

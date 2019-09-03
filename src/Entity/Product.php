<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\Column(type="float")
     */
    private $prijs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $afbeelding;

    /**
     * @ORM\Column(type="text")
     */
    private $omgschrijving;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cat")
     */
    private $cat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getPrijs(): ?float
    {
        return $this->prijs;
    }

    public function setPrijs(float $prijs): self
    {
        $this->prijs = $prijs;

        return $this;
    }

    public function getAfbeelding(): ?string
    {
        return $this->afbeelding;
    }

    public function setAfbeelding(string $afbeelding): self
    {
        $this->afbeelding = $afbeelding;

        return $this;
    }

    public function getOmgschrijving(): ?string
    {
        return $this->omgschrijving;
    }

    public function setOmgschrijving(string $omgschrijving): self
    {
        $this->omgschrijving = $omgschrijving;

        return $this;
    }

    public function getCat(): ?cat
    {
        return $this->cat;
    }

    public function setCat(?cat $cat): self
    {
        $this->cat = $cat;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $ValuteID;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumCode;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $CharCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nominal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="float")
     */
    private $Value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValuteID(): ?string
    {
        return $this->ValuteID;
    }

    public function setValuteID(string $ValuteID): self
    {
        $this->ValuteID = $ValuteID;

        return $this;
    }

    public function getNumCode(): ?int
    {
        return $this->NumCode;
    }

    public function setNumCode(int $NumCode): self
    {
        $this->NumCode = $NumCode;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->CharCode;
    }

    public function setCharCode(string $CharCode): self
    {
        $this->CharCode = $CharCode;

        return $this;
    }

    public function getNominal(): ?int
    {
        return $this->Nominal;
    }

    public function setNominal(int $Nominal): self
    {
        $this->Nominal = $Nominal;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->Value;
    }

    public function setValue(float $Value): self
    {
        $this->Value = $Value;

        return $this;
    }
}

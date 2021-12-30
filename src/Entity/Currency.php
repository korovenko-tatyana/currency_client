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
    private $valuteID;

    /**
     * @ORM\Column(type="integer")
     */
    private $numCode;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $charCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $nominal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    public function getId()
    {
        return $this->id;
    }

    public function getValuteID(): ?string
    {
        return $this->valuteID;
    }

    public function setValuteID($valuteID): self
    {
        $this->valuteID = $valuteID;

        return $this;
    }

    public function getNumCode(): ?int
    {
        return $this->numCode;
    }

    public function setNumCode($numCode): self
    {
        $this->numCode = $numCode;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode($charCode): self
    {
        $this->charCode = $charCode;

        return $this;
    }

    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    public function setNominal($nominal): self
    {
        $this->nominal = $nominal;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}

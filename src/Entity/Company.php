<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    // Identyfikator
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    // Nazwa
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private string $name;

    // NIP
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private string $nip;

    // Adres
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private string $address;

    // Miasto
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private string $city;

    // Kod pocztowy
    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     */
    private string $postalCode;

    // Gettery i Settery
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getNip(): string
    {
        return $this->nip;
    }
    public function setNip(string $nip): void
    {
        $this->nip = $nip;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
    public function getCity(): string
    {
        return $this->city;
    }
    public function setCity(string $city): void
    {
        $this->city = $city;
    }
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}

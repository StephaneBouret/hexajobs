<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeImmutable;

final class Company extends Entity
{
    private ?int $idCompany = null;
    private string $name = '';
    private string $slug = '';
    private string $address = '';
    private string $postalCode = '';
    private string $city = '';
    private ?string $url = null;
    private string $description = '';
    private string $siret = '';
    private ?DateTimeImmutable $createdAt;
    private int $offersCount = 0;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getIdCompany(): ?int
    {
        return $this->idCompany;
    }

    public function setIdCompany(int $idCompany): void
    {
        $this->idCompany = $idCompany;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = trim($slug);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = trim($address);
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = trim($postalCode);
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = trim($city);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = trim((string) $url);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): void
    {
        $this->siret = trim($siret);
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable|string $createdAt): void
    {
        if (is_string($createdAt)) {
            $createdAt = new DateTimeImmutable($createdAt);
        }

        $this->createdAt = $createdAt;
    }

    public function getOffersCount(): int
    {
        return $this->offersCount;
    }

    public function setOffersCount(int|string $offersCount): void
    {
        $this->offersCount = (int) $offersCount;
    }

    public function getShortDescription(int $length = 120): string
    {
        if (mb_strlen($this->description) <= $length) {
            return $this->description;
        }

        return mb_substr($this->description, 0, $length) . '...';
    }
}

<?php

declare(strict_types=1);

namespace App\Entities;

use Cocur\Slugify\Slugify;
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

    /**
     * Génère un slug à partir d'un texte
     */
    public static function slugify(string $text): string 
    {
        $slugify = new Slugify();

        $slug = $slugify->slugify($text);
        return $slug !== '' ? $slug : 'entreprise';
    }

    public function getIdCompany(): ?int
    {
        return $this->idCompany;
    }

    public function setIdCompany(int $idCompany): self
    {
        $this->idCompany = $idCompany;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = trim($name);
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = trim($slug);
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = trim($address);
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = trim($postalCode);
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = trim($city);
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $url = $url !== null ? trim($url) : null;
        $this->url = $url !== '' ? $url : null;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = trim($description);
        return $this;
    }

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = trim($siret);
        return $this;
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

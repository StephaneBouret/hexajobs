<?php

declare(strict_types=1);

namespace App\Entities;

use App\Enum\ContractType;
use DateTimeImmutable;

final class Offer extends Entity
{
    private ?int $idOffer = null;
    private string $title = '';
    private string $slug = '';
    private string $description = '';
    private string $location = '';
    private string $contract = ContractType::CDI->value;
    private string $salary = '';
    private string $status = '';
    private ?DateTimeImmutable $createdAt;
    private ?int $idCategory = null;
    private ?int $idCompany = null;

    private string $companyName = '';
    private string $categoryName = '';

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getIdOffer(): ?int
    {
        return $this->idOffer;
    }

    public function setIdOffer(int $idOffer): void
    {
        $this->idOffer = $idOffer;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = trim($title);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = trim($slug);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = trim($location);
    }

    public function getContract(): string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = ContractType::tryFrom($contract)?->value
            ?? ContractType::CDI->value;

        return $this;
    }

    public function getContractEnum(): ContractType
    {
        return ContractType::tryFrom($this->contract)
            ?? ContractType::CDI;
    }

    public function getSalary(): string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): void
    {
        $this->salary = trim($salary);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = trim($status);
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable|string $d): void
    {
        if (is_string($d)) {
            $d = new DateTimeImmutable($d);
        }
        $this->createdAt = $d;
    }

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function setIdCategory(?int $idCategory): void
    {
        $this->idCategory = $idCategory;
    }

    public function getIdCompany(): ?int
    {
        return $this->idCompany;
    }

    public function setIdCompany(?int $idCompany): void
    {
        $this->idCompany = $idCompany;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = trim($companyName);
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = trim($categoryName);
    }
}

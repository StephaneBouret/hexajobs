<?php

declare(strict_types=1);

namespace App\Entities;

use App\Enum\CandidatureStatus;
use DateTimeImmutable;

final class Candidature extends Entity
{
    private ?int $idCandidature = null;
    private string $cv = '';
    private string $coverLetter = '';
    private string $status = CandidatureStatus::EN_ATTENTE->value;
    private ?DateTimeImmutable $createdAt = null;
    private ?int $idOffer = null;
    private ?int $idUser = null;

    private string $offerTitle = '';
    private string $offerSlug = '';
    private string $companyName = '';

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getIdCandidature(): ?int
    {
        return $this->idCandidature;
    }

    public function setIdCandidature(int $id): self
    {
        $this->idCandidature = $id;
        return $this;
    }

    public function getCv(): string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;
        return $this;
    }

    public function getCoverLetter(): string
    {
        return $this->coverLetter;
    }

    public function setCoverLetter(string $coverLetter): self
    {
        $this->coverLetter = trim($coverLetter);
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = CandidatureStatus::tryFrom($status)?->value ?? CandidatureStatus::EN_ATTENTE->value;
        return $this;
    }

    public function getStatusEnum(): CandidatureStatus
    {
        return CandidatureStatus::tryFrom($this->status)
            ?? CandidatureStatus::EN_ATTENTE;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string|DateTimeImmutable|null $createdAt): self
    {
        if (is_string($createdAt) && $createdAt !== '') {
            $createdAt = new DateTimeImmutable($createdAt);
        }

        $this->createdAt = $createdAt instanceof DateTimeImmutable ? $createdAt : null;
        return $this;
    }

    public function getIdOffer(): ?int
    {
        return $this->idOffer;
    }

    public function setIdOffer(int $id): self
    {
        $this->idOffer = $id;
        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $id): self
    {
        $this->idUser = $id;
        return $this;
    }

    public function setTitle(string $title): void
    {
        $this->offerTitle = trim((string) $title);
    }

    public function getOfferTitle(): string
    {
        return $this->offerTitle;
    }

    public function setSlug(string $slug): void
    {
        $this->offerSlug = trim($slug);
    }

    public function getOfferSlug(): string
    {
        return $this->offerSlug;
    }

    public function getCompanyName(): string 
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self 
    {
        $this->companyName = trim($companyName);
        return $this;
    }
}

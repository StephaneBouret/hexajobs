<?php

declare(strict_types=1);

namespace App\Entities;

use App\Security\Roles;
use DateTimeImmutable;

final class User extends Entity
{
    private ?int $idUser = null;
    private string $firstname = '';
    private string $lastname = '';
    private string $email = '';
    private string $password = '';
    private string $role = Roles::USER;
    private ?DateTimeImmutable $createdAt = null;
    private ?int $idCompany = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = mb_convert_case(trim($firstname), MB_CASE_TITLE, 'UTF-8');
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = mb_convert_case(trim($lastname), MB_CASE_TITLE, 'UTF-8');
        return $this;
    }

    public function getFullName(): string
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = mb_strtolower(trim($email), 'UTF-8');
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = trim($password);
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $role = strtoupper(trim($role));

        $this->role = Roles::isValid($role)
            ? $role
            : Roles::USER;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return Roles::can($this->role, $role);
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

    public function getIdCompany(): ?int
    {
        return $this->idCompany;
    }

    public function setIdCompany(?int $idCompany): self
    {
        $this->idCompany = $idCompany;
        return $this;
    }

    public function toSessionArray(): array
    {
        return [
            'id' => $this->idUser,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'role' => $this->role,
            'id_company' => $this->idCompany,
        ];
    }
}

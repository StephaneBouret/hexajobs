<?php

declare(strict_types=1);

namespace App\Entities;

final class Category extends Entity
{
    private ?int $idCategory = null;
    private string $name = '';
    private string $slug = '';
    private int $offersCount = 0;

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function setIdCategory(int $idCategory): self
    {
        $this->idCategory = $idCategory;

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

    public function getOffersCount(): int
    {
        return $this->offersCount;
    }

    public function setOffersCount(int|string $offersCount): self
    {
        $this->offersCount = (int) $offersCount;

        return $this;
    }
}

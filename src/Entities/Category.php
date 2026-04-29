<?php

declare(strict_types=1);

namespace App\Entities;

final class Category extends Entity
{
    private ?int $idCategory = null;
    private string $name = '';
    private string $slug = '';

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
        $this->name = trim($slug);

        return $this;
    }
}

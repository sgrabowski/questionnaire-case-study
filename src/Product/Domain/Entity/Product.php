<?php

namespace App\Product\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

final class Product
{
    public function __construct(
        private UuidInterface $id,
        private string $name,
        private UuidInterface $categoryId,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function categoryId(): UuidInterface
    {
        return $this->categoryId;
    }
}

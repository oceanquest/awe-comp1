<?php

namespace awe;

class ShopProduct
{
    private string $id;
    private string $title;
    private string $mainName;
    private string $firstName;
    protected float $price;

    public function __construct(
        string $id,
        string $title,
        string $firstName,
        string $mainName,
        float $price
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->firstName = $firstName;
        $this->mainName = $mainName;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMainName(): string
    {
        return $this->mainName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFullName(): string
    {
        $fullName = trim($this->firstName . ' ' . $this->mainName);

        return $fullName;
    }
}
<?php

namespace App\Models;

namespace App\Models;

class User extends BaseModel
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $created;
    protected ?string $deleted;
    protected ?string $notes;

    // Getter and setter methods for the properties

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setCreated(string $created): void
    {
        $this->created = $created;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function setDeleted(?string $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function getDeleted(): ?string
    {
        return $this->deleted;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getProperties(): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'created' => $this->created ?? null,
            'deleted' => $this->deleted ?? null,
            'notes' => $this->notes ?? null,
        ];
    }
}

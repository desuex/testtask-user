<?php

namespace App\Models;

abstract class BaseModel
{
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            // Check if the property exists in the class
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function getProperties();
}

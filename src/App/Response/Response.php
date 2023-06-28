<?php

namespace App\Response;

abstract class Response
{
    protected array|string|null $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    abstract public function send();
}
<?php

namespace App\Response;

abstract class Response implements ResponseInterface
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

}
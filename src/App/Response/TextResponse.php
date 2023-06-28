<?php

namespace App\Response;

class TextResponse extends Response
{

    public function send()
    {
        echo $this->content;
    }
}
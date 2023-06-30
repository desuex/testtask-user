<?php

namespace App\Response;

use App\Models\BaseModel;

class ModelResponse extends JsonResponse
{

    public function __construct($content)
    {
        parent::__construct(null);
        if ($content instanceof BaseModel)
        $this->content = json_encode($content->getProperties());
    }
}
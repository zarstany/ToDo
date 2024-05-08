<?php

namespace App\Http\V1\Transformers;

use League\Fractal\TransformerAbstract as FractalTransformerAbstract;

class TransformerAbstract extends FractalTransformerAbstract
{
    protected string $resource;

    public function getResource(): string
    {
        return $this->resource;
    }
}

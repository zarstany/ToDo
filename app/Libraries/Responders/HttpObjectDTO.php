<?php

namespace App\Libraries\Responders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use stdClass;

class HttpObjectDTO
{
    private array $body = [];

    private array $headers = [];

    private array $metadata = [];

    private int $status = 200;

    private array|SCollection|Collection $collection;

    private Model|stdClass|array|Item $item;

    public function setItem(Model|stdClass|array|Item $item): HttpObjectDTO
    {
        $this->item = $item;

        return $this;
    }

    public function getItem(): Model|stdClass|array|Item
    {
        return $this->item;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function setBody(array $body): HttpObjectDTO
    {
        $this->body = $body;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): HttpObjectDTO
    {
        $this->headers = $headers;

        return $this;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): HttpObjectDTO
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): HttpObjectDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getCollection(): array|SCollection|Collection
    {
        return $this->collection;
    }

    public function setCollection(array|SCollection|Collection $collection): HttpObjectDTO
    {
        $this->collection = $collection;

        return $this;
    }
}

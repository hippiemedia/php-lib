<?php declare(strict_types=1);

namespace Hippiemedia;

final class Resource
{
    public $url;
    public $state;
    public $links;
    public $operations;
    public $embedded;

    public function __construct(string $url, $state, array $links = [], array $operations = [], array $embedded = [])
    {
        $this->url = $url;
        $this->state = $state;
        $this->links = $links;
        $this->operations = $operations;
        $this->embedded = $embedded;
    }
}

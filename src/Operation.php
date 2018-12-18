<?php declare(strict_types=1);

namespace Hippiemedia;

use Functional as f;

final class Operation
{
    public $rel;
    public $method;
    public $url;
    public $templated;
    public $title;
    public $description;
    public $fields;
    public $links;

    public function __construct(string $rel, string $method, string $url, bool $templated, string $title, string $description = '', array $fields = [], array $links = [])
    {
        $this->rel = $rel;
        $this->method = $method;
        $this->url = $url;
        $this->templated = $templated;
        $this->title = $title;
        $this->description = $description;
        $this->fields = $fields;
        $this->links = $links;
    }

    public function rel(): string
    {
        return $this->rel;
    }
}

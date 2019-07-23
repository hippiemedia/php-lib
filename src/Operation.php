<?php declare(strict_types=1);

namespace Hippiemedia;

use Functional as f;
use Hippiemedia\Link;
use Hippiemedia\Field;
use Rize\UriTemplate;

final class Operation
{
    public $rel;
    public $method;
    public $url;
    public $templated;
    public $title;
    public $description;
    public $urlFields = [];
    public $fields = [];
    public $links;
    public $extra = [];
    private $uriTemplate;

    public function __construct(string $rel, string $method, string $url, string $title, string $description = '', array $fields = [], array $links = [], array $extra = [])
    {
        $this->rel = $rel;
        $this->method = $method;
        $this->url = $url;
        $this->uriTemplate = new UriTemplate($this->url);
        $parsed = $this->uriTemplate->extract($this->url, $this->url);
        $this->urlFields = array_map(function($key, $value) {
            return (object)['name' => $key, 'value' => $value];
        }, array_keys($parsed), $parsed);
        $this->templated = !empty($this->urlFields);
        $this->title = $title;
        $this->description = $description;
        $this->fields = $fields;
        $this->links = $links;
        $this->extra = $extra;
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['rel'] ?? 'rel',
            $data['method'] ?? 'method',
            $data['url'] ?? 'url',
            $data['title'] ?? 'title',
            $data['description'] ?? 'description',
            $data['fields'] ?? [Field::whatever()],
            $data['links'] ?? [Link::whatever()]
        );
    }

    public function rel(): string
    {
        return $this->rel;
    }
}

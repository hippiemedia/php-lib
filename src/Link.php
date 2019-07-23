<?php declare(strict_types=1);

namespace Hippiemedia;

use Rize\UriTemplate;


final class Link
{
    public $rel;
    public $href;
    public $fields = [];
    public $templated;
    public $type;
    public $title;
    public $description;
    public $isDeprecated;
    public $extra = [];
    private $uriTemplate;

    public function __construct(array $rel, string $href, string $title = null, string $description = null, string $type = null, bool $isDeprecated = false, array $extra = [])
    {
        $this->rel = $rel;
        $this->href = $href;
        $this->uriTemplate = new UriTemplate($this->href);
        $parsed = $this->uriTemplate->extract($this->href, $this->href);
        $this->fields = array_map(function($key, $value) {
            return (object)['name' => $key, 'value' => $value];
        }, array_keys($parsed), $parsed);
        $this->templated = !empty($this->fields);
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->isDeprecated = $isDeprecated;
        $this->extra = $extra;
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['rel'] ?? ['rel'],
            $data['href'] ?? 'href',
            $data['title'] ?? 'title',
            $data['description'] ?? 'description',
            $data['type'] ?? 'type'
        );
    }

    public function rels(): array
    {
        return $this->rel;
    }

    public function rel(): string
    {
        return $this->rel[0] ?? 'self';
    }
}

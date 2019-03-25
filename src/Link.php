<?php declare(strict_types=1);

namespace Hippiemedia;

final class Link
{
    public $rel;
    public $href;
    public $templated;
    public $type;
    public $title;
    public $description;
    public $isDeprecated;

    public function __construct(array $rel, string $href, bool $templated = false, string $title = null, string $description = null, string $type = null, bool $isDeprecated = false)
    {
        $this->rel = $rel;
        $this->href = $href;
        $this->templated = $templated;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->isDeprecated = $isDeprecated;
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['rel'] ?? ['rel'],
            $data['href'] ?? 'href',
            $data['templated'] ?? false,
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

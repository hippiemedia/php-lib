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

    public function __construct(string $rel, string $href, bool $templated = false, string $title = null, string $type = null)
    {
        $this->rel = $rel;
        $this->href = $href;
        $this->templated = $templated;
        $this->title = $title;
        $this->type = $type;
    }

    public function rel(): string
    {
        return $this->rel;
    }
}

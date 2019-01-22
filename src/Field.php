<?php declare(strict_types=1);

namespace Hippiemedia;

final class Field
{
    public $name;
    public $type;
    public $required;
    public $value;
    public $title;

    public function __construct(string $name, string $type, bool $required, string $value = null, string $title = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->value = $value;
        $this->title = $title;
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['name'] ?? 'name',
            $data['type'] ?? 'text',
            $data['required'] ?? true,
            $data['value'] ?? 'value',
            $data['title'] ?? 'title'
        );
    }
}

<?php declare(strict_types=1);

namespace Hippiemedia;

final class Field
{
    public $name;
    public $value;

    public function __construct(string $name, bool $required, string $value = null)
    {
        $this->name = $name;
        $this->required = $required;
        $this->value = $value;
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['name'] ?? 'name',
            $data['required'] ?? true,
            $data['value'] ?? 'value'
        );
    }
}

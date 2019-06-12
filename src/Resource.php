<?php declare(strict_types=1);

namespace Hippiemedia;

use Hippiemedia\Link;
use Functional as f;

final class Resource
{
    public $url;
    public $state;
    public $links;
    public $operations;
    public $embedded;
    public $isDeprecated;

    public function __construct(string $url, array $state, array $links = [], array $operations = [], array $embedded = [], bool $isDeprecated = false)
    {
        $this->url = $url;
        $this->state = $state;
        $this->isDeprecated = $isDeprecated;
        $this->operations = $operations;
        $this->embedded = $embedded;

        $this->links = array_merge([$this->selfLink()], $links, array_reduce(array_keys($embedded), function($carry, $rel) use($embedded) {
            $resources = $embedded[$rel];
            return array_merge($carry, f\invoke($resources, 'selfLink', [[$rel]]));
        }, []));
    }

    public static function whatever(array $data = [])
    {
        return new self(
            $data['url'] ?? 'url',
            $data['state'] ?? ['state'],
            $data['links'] ?? [Link::whatever()],
            $data['operations'] ?? [Operation::whatever()],
            $data['embedded'] ?? ['rel' => [self::whatever(['embedded' => []])]],
            $data['is_deprecated'] ?? false
        );
    }

    public function selfLink(array $rels = ['self']): Link
    {
        return new Link($rels, $this->url, null, null, null, $this->isDeprecated);
    }
}

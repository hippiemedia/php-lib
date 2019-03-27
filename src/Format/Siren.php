<?php declare(strict_types=1);

namespace Hippiemedia\Format;

use Functional as f;
use Hippiemedia\Format;
use Hippiemedia\Resource;
use Hippiemedia\Link;
use DocteurKlein\JsonChunks\Encode;

final class Siren implements Format
{
    public function accepts(): string
    {
        return 'application/vnd.siren+json';
    }

    public function __invoke(Resource $resource): iterable
    {
        return Encode::from($this->normalize($resource));
    }

    private function normalize(Resource $resource): iterable
    {
        return [
            'class' => [],
            'properties' => $resource->state,
            'links' => f\map(array_values($resource->links), function($link) {
                return [
                    'rel' => $link->rel,
                    'class' => [],
                    'href' => $link->href,
                    'title' => $link->title,
                    'type' => $link->type,
                ];
            }),
            'entities' => array_reduce(array_keys($resource->embedded), function($acc, $rel) use($resource) {
                $resources = $resource->embedded[$rel];
                return array_merge($acc, f\map($resources, function($resource) use($rel) {
                    return array_merge($this->normalize($resource), ['rel' => [$rel]]);
                }));
            }, []),
            'actions' => f\map(array_values($resource->operations), function($operation) {
                return [
                    'name' => $operation->rel,
                    'class' => [],
                    'method' => $operation->method,
                    'href' => $operation->url,
                    'title' => $operation->title,
                    'type' => 'application/x-www-form-urlencoded',
                    'fields' => $operation->fields, // todo map correctly
                ];
            }),
        ];
    }
}

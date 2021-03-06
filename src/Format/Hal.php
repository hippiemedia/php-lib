<?php declare(strict_types=1);

namespace Hippiemedia\Format;

use Functional as f;
use Hippiemedia\Format;
use Hippiemedia\Resource;
use Hippiemedia\Link;

final class Hal implements Format
{
    public function accepts(): string
    {
        return 'application/hal+json';
    }

    public function __invoke(Resource $resource): iterable
    {
        $linksByRel = array_merge(
            f\group($resource->links, f\invoker('rel')),
            f\group(f\map($resource->operations, function($operation) {
                return new Link([$operation->rel], $operation->url, $operation->title, $operation->description, 'application/prs.hal-forms+json');
            }), f\invoker('rel')),
        );

        $operationsByRel = f\group($resource->operations, f\invoker('rel'));

        return array_merge($resource->state, [
            '_links' => f\map($linksByRel, function($links) {
                return f\map(array_values($links), function($link) {
                    return array_merge([
                        'href' => $link->href,
                    ], array_filter([
                        'templated' => $link->templated,
                        'type' => $link->type,
                        'title' => $link->title,
                        'description' => $link->description,
                        'deprecation' => $link->isDeprecated,
                        'tags' => $link->extra['tags'] ?? [],
                    ]));
                });
            }),
            '_embedded' => array_merge(f\map($operationsByRel, function($operations) {
                return f\map(array_values($operations), function($operation) {
                    return $this(new Resource($operation->url, [
                        '_templates' => [
                            'default' => [
                                'title' => $operation->title,
                                'description' => $operation->description,
                                'method' => $operation->method,
                                'contentType' => 'application/x-www-form-urlencoded',
                                'properties' => $operation->fields,
                                'tags' => $operation->extra['tags'] ?? [],
                            ],
                        ],
                    ], array_merge([new Link(['self'], $operation->url)], $operation->links), []));
                });
            }), f\map($resource->embedded, function($resources) {
                return f\map($resources, $this);
            }, $this))
        ]);
    }
}

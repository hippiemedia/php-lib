<?php declare(strict_types=1);

namespace Hippiemedia\Format;

use Functional as f;
use Hippiemedia\Format;
use Hippiemedia\Resource;
use Hippiemedia\Link;
use DocteurKlein\JsonChunks\Encode;

final class Hal implements Format
{
    public function accepts(): string
    {
        return 'application/hal+json';
    }

    public function __invoke(Resource $resource): iterable
    {
        return Encode::from($this->normalize($resource));
    }

    private function normalize(Resource $resource): iterable
    {
        $linksByRel = array_merge(
            f\group($resource->links, f\invoker('rel')),
            f\group(f\map($resource->operations, function($operation) {
                return new Link([$operation->rel], $operation->url, $operation->templated, $operation->title, $operation->description, 'application/prs.hal-forms+json');
            }), f\invoker('rel')),
        );

        $operationsByRel = f\group($resource->operations, f\invoker('rel'));

        return array_merge($resource->state, [
            '_links' => f\map($linksByRel, function($links) {
                return f\map(array_values($links), function($link) {
                    return array_filter([
                        'href' => $link->href,
                        'templated' => $link->templated,
                        'type' => $link->type,
                        'title' => $link->title,
                        'description' => $link->description,
                        'deprecation' => $link->isDeprecated,
                    ]);
                });
            }),
            '_embedded' => array_merge(f\map($operationsByRel, function($operations) {
                return f\map(array_values($operations), function($operation) {
                    return $this->normalize(new Resource($operation->url, [
                        '_templates' => [
                            'default' => [
                                'title' => $operation->title,
                                'description' => $operation->description,
                                'method' => $operation->method,
                                'contentType' => 'application/x-www-form-urlencoded',
                                'properties' => $operation->fields,
                                //'properties' => f\map($operation->fields, function($field) {
                                //    return [
                                //        'name' => $field->name,
                                //        'description' => $field->description,
                                //        'required' => false,
                                //        'type' => 'text',
                                //        'multiple' => true,
                                //        'example' => '781096508204',
                                //    ];
                                //}),
                            ],
                        ],
                    ], array_merge([new Link(['self'], $operation->url, $operation->templated)], $operation->links), []));
                });
            }), f\map($resource->embedded, function($resources) {
                return array_map([$this, 'normalize'], $resources);
            }))
        ]);
    }
}

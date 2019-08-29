<?php declare(strict_types=1);

namespace Hippiemedia\Format;

use Functional as f;
use Hippiemedia\Format;
use Hippiemedia\Operation;
use Hippiemedia\Resource;
use Hippiemedia\Link;

final class OpenApi3 implements Format
{
    private $overrides;

    public function __construct(array $overrides)
    {
        $this->overrides = $overrides;
    }

    public function accepts(): string
    {
        return 'application/oas3+json';
    }

    public function __invoke(Resource $resource): iterable
    {
        return array_replace_recursive([
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'oas3 api',
                'version' => '0.0.1',
            ]],
            ['paths' => array_merge_recursive(
                iterator_to_array((function() use($resource) {
                    /** @var Link $link */
                    foreach ($resource->links as $link) {
                        $path = parse_url($link->href, PHP_URL_PATH);
                        yield $path => [
                            'get' => array_merge([
                                'operationId' => $link->rel(),
                                'summary' => $link->title,
                                'description' => $link->description,
                                'parameters' => f\map($link->fields, function($field) {
                                    return [
                                        'name' => $field->name,
                                        'in' => 'path',
                                        'required' => true,
                                        'schema' => ['type' => 'string'],
                                    ];
                                }),
                                'responses' => ['default' => ['description' => 'ok']],
                            ], $link->extra()),
                        ];
                    }
                })()),
                iterator_to_array((function() use($resource) {
                    /** @var Operation $operation */
                    foreach ($resource->operations as $operation) {
                        $path = parse_url($operation->url, PHP_URL_PATH);
                        yield $path => [
                            strtolower($operation->method) => array_filter(array_merge([
                                'operationId' => $operation->rel,
                                'summary' => $operation->title,
                                'description' => $operation->description,
                                'parameters' => f\map($operation->urlFields, function($field) {
                                    return [
                                        'name' => $field->name,
                                        'in' => 'path',
                                        'required' => true,
                                        'schema' => ['type' => 'string'],
                                    ];
                                }),
                                'requestBody' => strtolower($operation->method) === 'delete' ? null : [
                                    'content' => array_filter(array_merge([
                                        'application/x-www-form-urlencoded' => [
                                            'schema' => array_filter([
                                                'type' => 'object',
                                                'properties' => iterator_to_array((function() use($operation) {
                                                    foreach ($operation->fields as $field) {
                                                        yield $field->name => [
                                                            'type' => 'string',
                                                        ];
                                                    }
                                                })()),
                                            ]),
                                        ]],
                                        array_reduce([$operation], function($carry, $operation) {
                                            $link = current(array_filter($operation->links, function($link) {
                                                return $link->rel == ['request-schema'];
                                            }));
                                            if ($link) {
                                                $carry = [
                                                    'application/json' => [
                                                        'schema' => [
                                                            '$ref' => $link->href,
                                                        ],
                                                    ],
                                                ];
                                            }
                                            return $carry;
                                        }, []),
                                    )),
                                ],
                                'responses' => ['default' => [
                                    'description' => 'ok',
                                ]],
                            ], $operation->extra)),
                        ];
                    }
                })()),
            )],
            $this->overrides
        );
    }
}

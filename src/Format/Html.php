<?php declare(strict_types=1);

namespace Hippiemedia\Format;

use Functional as f;
use Hippiemedia\Format;
use Hippiemedia\Resource;
use Hippiemedia\Operation;

final class Html implements Format
{
    public function accepts(): string
    {
        return 'text/html';
    }

    public function __invoke(Resource $resource): iterable
    {
        $state = print_r($resource->state, true);
        yield '<div>';
        yield "<pre>{$state}</pre>";
        yield from $this->links($resource);
        yield from $this->operations($resource);
        yield '</div>';
    }

    private function links(Resource $resource): iterable
    {
        yield '<ul>';
        foreach ($resource->links as $link) {
            yield <<<HTML
                <li><a href="{$link->href}">{$link->title}</a></li>
            HTML;
        }
        yield '</ul>';
    }

    private function operations(Resource $resource): iterable
    {
        foreach ($resource->operations as $operation) {
            yield <<<HTML
                <form action="{$operation->url}" method="{$operation->method}">
                    <fieldset>
                        <legend>{$operation->title}</legend>
                HTML
            ;
            yield from $this->fields($operation);

            yield <<<HTML
                    </fieldset>
                    <input type="submit" />
                </form>
            HTML;
        }
    }

    private function fields(Operation $operation): iterable
    {
        foreach ($operation->fields as $field) {
            yield <<<HTML
                <input name="{$field->name}" value="{$field->value}" />
            HTML;
        }
    }
}

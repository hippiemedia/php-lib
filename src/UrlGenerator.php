<?php declare(strict_types=1);

namespace Hippiemedia;

interface UrlGenerator
{
    function url(string $route, array $params = []): string;

    function template(string $route): string;
}

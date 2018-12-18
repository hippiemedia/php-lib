<?php declare(strict_types=1);

namespace Hippiemedia;

use Hippiemedia\Resource;

interface Format
{
    public function accepts(): string;

    public function __invoke(Resource $resource): iterable;
}

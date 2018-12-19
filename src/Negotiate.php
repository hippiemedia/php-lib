<?php declare(strict_types=1);

namespace Hippiemedia;

use Hippiemedia\Format;

interface Negotiate
{
    public function __invoke(?string $accept): Format;
}

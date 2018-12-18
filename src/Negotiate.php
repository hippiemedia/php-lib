<?php declare(strict_types=1);

namespace Hippiemedia;

use Functional as f;
use Negotiation\Negotiator;
use Hippiemedia\Format;

final class Negotiate
{
    private $negotiator;
    private $formats;
    private $default;

    public function __construct(Negotiator $negotiator, string $default, Format ...$formats)
    {
        $this->negotiator = $negotiator;
        $this->default = $default;
        $this->formats = $formats;
    }

    public function __invoke(?string $accept): Format
    {
        $accept = $accept ?: $this->default;
        $possible = f\group($this->formats, f\invoker('accepts'));
        $mediaType = $this->negotiator->getBest($accept, f\map($this->formats, f\invoker('accepts')));

        $negotiated = $mediaType ? $mediaType->getValue() : null;
        if (!$negotiated) {
            $negotiated = key($possible);
        }
        return current($possible[$negotiated]);
    }
}

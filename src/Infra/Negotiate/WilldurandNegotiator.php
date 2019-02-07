<?php declare(strict_types=1);

namespace Hippiemedia\Infra\Negotiate;

use Functional as f;
use Negotiation\Negotiator;
use Hippiemedia\Format;
use Hippiemedia\Negotiate;

final class WilldurandNegotiator implements Negotiate
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

    public function availableFormats(Format $except): array
    {
        return array_filter($this->formats, function($format) use($except) {
            return $format->accepts() !== $except->accepts();
        });
    }
}

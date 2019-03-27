<?php

namespace spec\Hippiemedia\Format;

use Hippiemedia\Format\Siren;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hippiemedia\Resource;

class HtmlSpec extends ObjectBehavior
{
    function it_formats_html()
    {
        $iterable = $this(Resource::whatever());

        $html = implode("\n", iterator_to_array($iterable->getWrappedObject(), false));
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML($html);
    }
}

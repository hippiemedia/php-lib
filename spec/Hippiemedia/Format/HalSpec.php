<?php

namespace spec\Hippiemedia\Format;

use Hippiemedia\Format\Hal;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hippiemedia\Resource;

class HalSpec extends ObjectBehavior
{
    function it_formats_hal()
    {
        $hal = $this(Resource::whatever());

        $hal['_links']->shouldHaveCount(1);
    }
}

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

        $hal['_links']->shouldHaveCount(2);
    }

    function it_deprecates_links()
    {
        $hal = $this(Resource::whatever([
            'embedded' => ['reltype' => [Resource::whatever(['is_deprecated' => true])]],
        ]));
        $hal['_links']['reltype'][0]['deprecation']->shouldBe(true);
        $hal['_links']->shouldHaveCount(3);
    }
}

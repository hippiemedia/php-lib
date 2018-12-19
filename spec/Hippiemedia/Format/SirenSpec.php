<?php

namespace spec\Hippiemedia\Format;

use Hippiemedia\Format\Siren;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hippiemedia\Resource;

class SirenSpec extends ObjectBehavior
{
    function it_formats_siren()
    {
        $siren = $this(Resource::whatever());

        $siren['links']->shouldHaveCount(2);
    }
}

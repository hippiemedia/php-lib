<?php

namespace spec\Hippiemedia;

use Hippiemedia\Link;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LinkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(['rel'], 'href', $templated = true, 'title', $type = 'application/json');
        $this->rel->shouldBe(['rel']);
        $this->rel()->shouldBe('rel');
    }
}

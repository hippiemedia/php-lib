<?php

namespace spec\Hippiemedia;

use Hippiemedia\Resource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hippiemedia\Link;
use Hippiemedia\Operation;

class ResourceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('url', ['state'], [Link::whatever()], [Operation::whatever()], [Resource::whatever()]);
        $this->links->shouldHaveCount(1);
    }
}

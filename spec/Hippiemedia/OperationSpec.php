<?php

namespace spec\Hippiemedia;

use Hippiemedia\Operation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hippiemedia\Field;
use Hippiemedia\Link;

class OperationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('rel', 'PUT', 'url', 'title', 'description', [Field::whatever()], [Link::whatever()]);
        $this->rel->shouldBe('rel');
        $this->links->shouldHaveCount(1);
    }
}

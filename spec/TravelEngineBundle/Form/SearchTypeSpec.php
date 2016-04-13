<?php
/*******************************************************************************
 * This is closed source software, created by WWSH.
 * Copyright (c) 2016. All Rights Reserved.
 * No distribution, copying and sharing of any of the portions of this code 
 * is hereby allowed.
 * http://wwsh.io
 ******************************************************************************/

namespace spec\TravelEngineBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SearchTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TravelEngineBundle\Form\SearchType');
    }
}

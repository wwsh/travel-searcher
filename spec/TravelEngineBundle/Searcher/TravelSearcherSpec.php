<?php
/*******************************************************************************
 * This is closed source software, created by WWSH.
 * Copyright (c) 2016. All Rights Reserved.
 * No distribution, copying and sharing of any of the portions of this code 
 * is hereby allowed.
 * http://wwsh.io
 ******************************************************************************/

namespace spec\TravelEngineBundle\Searcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TravelEngineBundle\DataProvider\DataProviderInterface;

class TravelSearcherSpec extends ObjectBehavior
{
    function it_is_initializable(DataProviderInterface $dataProvider)
    {
        $this->beConstructedWith($dataProvider);
        $this->shouldHaveType('TravelEngineBundle\Searcher\TravelSearcher');
    }

    function it_fails_if_t_is_bigger_than_specified_range(DataProviderInterface $dataProvider)
    {
        $this->beConstructedWith($dataProvider);
        $params =
            [
                'country'    => 'Spain',
                'start_date' => '01.07.2016',
                'end_date'   => '05.07.2016', // this period is only 4 days long, but T = 10
                'T'          => 10
            ];

        $this->shouldThrow(new \RuntimeException('T exceeds specified date range'))->during('search', [$params]);

    }

    public function it_fails_on_incomplete_search_data(DataProviderInterface $dataProvider)
    {
        $this->beConstructedWith($dataProvider);
        $params =
            [
                'q'          => '',
                'start_date' => '01.01.2016',
                'end_date'   => '',
                'T'          => 10,
            ];

        $this->shouldThrow(new \RuntimeException('Bad dates or missing'))->during('search', [$params]);
    }
}

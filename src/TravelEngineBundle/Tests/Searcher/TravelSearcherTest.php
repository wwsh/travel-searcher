<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 WW Software House
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace TravelEngineBundle\Tests\Searcher;

use Mockery;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use TravelEngineBundle\DataProvider\JsonFileDataProvider;
use TravelEngineBundle\Searcher\TravelSearcher;

class TravelSearcherTest extends TestCase
{
    /**
     * @var TravelSearcher
     */
    private $searchEngine;

    public function setUp()
    {
        $dataProvider       = new JsonFileDataProvider(__DIR__ . '/../Resources/testdatabases/01.json');
        $this->searchEngine = new TravelSearcher($dataProvider);
    }

    public function test_countries_dont_return_invalid_cities()
    {
        $results = $this->searchEngine->search(['country' => 'Spain']);
        assertThat(count($results->having(['country' => 'Spain'])), is(4));
        assertThat(count($results->having(['city' => 'Toledo'])), is(1));
        assertThat(count($results->having(['city' => 'Barcelona'])), is(1));
        assertThat(count($results->having(['city' => 'Malaga'])), is(1));
        assertThat(count($results->having(['city' => 'Madrid'])), is(1));
        assertThat(count($results->having(['country' => 'Poland'])), is(0));
    }

    public function test_city_search_should_return_data_for_city_only()
    {
        $results = $this->searchEngine->search(['city' => 'Zakopane']);
        assertThat(count($results->having(['country' => 'Poland'])), is(1));
        assertThat(count($results->having(['country' => 'Spain'])), is(0));
        assertThat(count($results->having(['city' => 'Bydgoszcz'])), is(0));
    }

    public function test_returning_trips_within_user_date_range()
    {
        $results = $this->searchEngine->search(
            [
                'country'    => 'Spain',
                'start_date' => '01.07.2016',
                'end_date'   => '30.07.2016',
                'T'          => 10
            ]
        );
        assertThat(count($results), is(2));

        assertThat(count($results->having(
            ['promoted' => false, 'city' => 'Madrid', 'start_date' => '25.06.2016', 'end_date' => '10.07.2016']
        )), is(1));

        assertThat(count($results->having(
            ['promoted' => true, 'city' => 'Barcelona', 'start_date' => '14.07.2016', 'end_date' => '28.07.2016']
        )), is(1));

        assertThat(count($results->having(['city' => 'Lisbon'])), is(0));

        assertThat(count($results->having(['city' => 'Malaga'])), is(0));

        assertThat(count($results->having(['city' => 'Toledo'])), is(0));
    }

    public function test_query_parameter()
    {
        $results = $this->searchEngine->search(
            [
                'q' => 'Poland'
            ]
        );

        assertThat(count($results), is(3));

        $results = $this->searchEngine->search(
            [
                'q' => 'zakopane'
            ]
        );

        assertThat(count($results), is(1));
    }

    public function test_empty_query_should_return_all()
    {
        $results = $this->searchEngine->search(
            [

            ]
        );

        assertThat(count($results), is(8));
    }
}
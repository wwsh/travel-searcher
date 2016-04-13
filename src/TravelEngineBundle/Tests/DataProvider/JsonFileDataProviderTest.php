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

namespace TravelEngineBundle\Tests\DataProvider;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use TravelEngineBundle\DataProvider\DataProviderInterface;
use TravelEngineBundle\DataProvider\JsonFileDataProvider;

class JsonFileDataProviderTest extends TestCase
{
    /**
     * @var DataProviderInterface
     */
    private $tripDatabase;

    public function setUp()
    {
        $this->tripDatabase = new JsonFileDataProvider(__DIR__ . '/../Resources/testdatabases/01.json');
    }

    public function test_duration_parameter()
    {
        $this->tripDatabase->setCountry('Poland');
        $this->tripDatabase->setMinDuration(2);
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(3));

        $this->setUp();
        $this->tripDatabase->setCountry('Poland');
        $this->tripDatabase->setStartDateGreaterOrEqual('30.07.2016');
        $this->tripDatabase->setMinDuration(10);
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(1));
        assertThat($result->having(['city' => 'Bydgoszcz'])->count(), is(1));

        $this->setUp();
        $this->tripDatabase->setCountry('Poland');
        $this->tripDatabase->setStartDateGreaterOrEqual('10.08.2016');
        $this->tripDatabase->setMinDuration(10);
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(0));

        $this->setUp();
        $this->tripDatabase->setCountry('Poland');
        $this->tripDatabase->setStartDateGreaterOrEqual('30.07.2016');
        $this->tripDatabase->setMinDuration(10);
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(1));
        assertThat($result->having(['city' => 'Bydgoszcz'])->count(), is(1));
    }

    public function test_range_search()
    {
        $userStartDate = '01.01.2016';
        $userEndDate   = '16.06.2016';
        $this->tripDatabase->setCountry('Spain');
        $this->tripDatabase->setEndDateGreaterThan($userStartDate);
        $this->tripDatabase->setStartDateLessThan($userEndDate);
        $this->tripDatabase->setMinDuration(1);
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(1));
        $trip = $result[0];
        assertThat($trip->getCity(), is('Toledo'));
    }

    public function test_strict_search()
    {
        $this->tripDatabase->setStartDateGreaterOrEqual('01.09.2016');
        $this->tripDatabase->setEndDateLessOrEqual('30.09.2016');
        $this->tripDatabase->setCountry('Poland');
        $result = $this->tripDatabase->getData();
        assertThat($result->count(), is(1));
        $trip = $result[0];
        assertThat($trip->getCity(), is('Zakopane'));
    }


}
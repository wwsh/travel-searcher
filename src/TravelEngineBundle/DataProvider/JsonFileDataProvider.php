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

namespace TravelEngineBundle\DataProvider;

use TravelEngineBundle\Document\TripCollector;

/**
 * Class JsonFileDataProvider.
 * Demo app's default JSON-file based, database driver.
 * Not a real query builder - emulating its behaviour.
 * The database data is already read at object instantiation.
 * Filter functions do perform their duties on the dataset
 * in memory.
 * 
 * @package TravelEngineBundle\DataProvider
 */
class JsonFileDataProvider implements DataProviderInterface
{
    /**
     * @var TripCollector
     */
    private $collection;

    /**
     * JsonFileDataProvider constructor.
     * @param $filename Full path to the JSON file. In this example we are using .json from the Tests/ dir
     */
    public function __construct($filename)
    {
        $jsonContents     = json_decode(file_get_contents($filename), true);
        $this->collection = new TripCollector($jsonContents);
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->filterCollection(['country' => $country]);

        return $this;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->filterCollection(['city' => $city]);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setQuery($value)
    {
        $this->filterCollection(['q' => $value]);

        return $this;
    }

    /**
     * @param $start
     * @return $this
     */
    public function setStartDateGreaterOrEqual($start)
    {
        $this->filterCollection(['start_ge' => $start]);

        return $this;
    }

    /**
     * @param $end
     * @return $this
     */
    public function setEndDateLessOrEqual($end)
    {
        $this->filterCollection(['end_le' => $end]);

        return $this;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setStartDateLessThan($date)
    {
        $this->filterCollection(['start_l' => $date]);

        return $this;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setEndDateGreaterThan($date)
    {
        $this->filterCollection(['end_g' => $date]);

        return $this;
    }

    /**
     * @param $int
     * @return $this
     */
    public function setMinDuration($int)
    {
        $this->filterCollection(['duration' => $int]);

        return $this;
    }

    /**
     * @return $this|TripCollector
     */
    public function getData()
    {
        return $this->collection;
    }

    /**
     * @param $criteria
     * @return $this|TripCollector
     */
    private function filterCollection($criteria)
    {
        if (empty($criteria)) {
            return $this;
        }

        if (isset($criteria['city']) || isset($criteria['country']) || isset($criteria['q'])) {
            $this->collection = $this->collection->having($criteria);
        }

        $result = new TripCollector();

        if (!$this->collection->count()) {
            return $this;
        }

        foreach ($this->collection as $item) {
            // db.startDate >= startDate
            if (isset($criteria['start_ge']) && $item->isStartDateLess($criteria['start_ge'])) {
                continue;
            }
            // db.endDate <= endDate
            if (isset($criteria['end_le']) && $item->isEndDateGreater($criteria['end_le'])) {
                continue;
            }

            // db.startDate < startDate
            if (isset($criteria['start_l']) && !$item->isStartDateLess($criteria['start_l'])) {
                continue;
            }
            // db.endDate > endDate
            if (isset($criteria['end_g']) && !$item->isEndDateGreater($criteria['end_g'])) {
                continue;
            }

            if (isset($criteria['duration']) && $item->getDuration() < $criteria['duration']) {
                continue;
            }

            $result->addTrip($item);
        }

        $this->collection = $result;

        return $this;

    }


}
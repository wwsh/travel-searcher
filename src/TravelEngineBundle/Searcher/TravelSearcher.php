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

namespace TravelEngineBundle\Searcher;

use TravelEngineBundle\DataProvider\DataProviderInterface;
use TravelEngineBundle\Document\Trip;
use TravelEngineBundle\Document\TripCollector;

/**
 * Class TravelSearcher.
 * The main searching service. Should be a service.
 * 
 * @package TravelEngineBundle\Searcher
 */
class TravelSearcher
{

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    public function __construct($dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param $criteria
     * @return TripCollector
     */
    public function search($criteria)
    {
        $this->checkCriteria($criteria);
        $this->setSimpleCriteria($criteria);
        $this->setDateRangeCriteria($criteria);
        $results = $this->dataProvider->getData();
        $results = $this->checkDurationAndFilter($results, $criteria);
        $results = $this->bumpBestMatchingResults($results, $criteria);

        return $results;
    }

    /**
     * @param $criteria
     * @return mixed
     */
    public function setSimpleCriteria($criteria)
    {
        foreach ($criteria as $item => $value) {
            if ($item === 'q') {
                $this->dataProvider->setQuery($value);
            }
            if ($item === 'city') {
                $this->dataProvider->setCity($value);
            }
            if ($item === 'country') {
                $this->dataProvider->setCountry($value);
            }
        }
    }

    /**
     * Promoting trips whose:
     * - s/e date points lie strictly within user specified date range
     * - date intersection with is at least T
     *
     * @param TripCollector $results
     * @param               $criteria
     * @return mixed
     */
    private function bumpBestMatchingResults(TripCollector $results, $criteria)
    {
        $result = new TripCollector();

        if (!isset($criteria['start_date']) || !isset($criteria['end_date']) || !$results->count()) {
            return $results;
        }

        foreach ($results as $trip) {
            // if specified trip lies not strictly within given user date range, not interested
            if ($trip->isStartDateLess($criteria['start_date']) ||
                $trip->isEndDateGreater($criteria['end_date'])
            ) {
                $result->addTrip($trip);
                continue;
            }
            // if trip duration is shorter, than expected, not interested
            if ($trip->getDuration() < $criteria['T']) {
                $result->addTrip($trip);
            }

            $trip->setPromoted(true);
            $result->unshiftTrip($trip); // add right at the beginning
        }

        return $result;
    }


    /**
     * Checks if duration of returned results matches the user given value,
     * taking date range boundaries into account.
     * Note: more advanced, thus could not be included into the low level driver.
     *
     * @param $results
     * @param $criteria
     * @return TripCollector
     */
    private function checkDurationAndFilter(TripCollector $results, $criteria)
    {
        if (!$results->count() || !isset($criteria['T']) ||
            !isset($criteria['start_date']) || !isset($criteria['end_date'])
        ) {
            return $results;
        }

        $result = new TripCollector();

        $userStartDate = $criteria['start_date'];
        $userEndDate   = $criteria['end_date'];

        foreach ($results as $trip) {
            // checking the case, where trip begins earlier than user wanted
            if ($trip->isStartDateLess($userStartDate)) {
                $duration = $trip->getDuration() - $trip->getStartDateDiff($userStartDate);
                if ($duration < $criteria['T']) {
                    continue; // from the user's point of view, the trip is shorter than requested
                }
            }

            // checking the case, where trip ends later than user expectations
            if ($trip->isEndDateGreater($userEndDate)) {
                $duration = $trip->getDuration() - $trip->getEndDateDiff($userEndDate);
                if ($duration < $criteria['T']) {
                    continue; // from the user's point of view, the trip is shorter than requested
                }
            }

            $result->addTrip($trip);
        }

        return $result;
    }

    /**
     * Filtering and returning trips whose:
     * - trip dates overlap either side user specified date range
     * - date intersection with is at least T
     *
     * @param $criteria
     * @return TripCollector
     */
    private function setDateRangeCriteria($criteria)
    {
        if (!isset($criteria['T'])) {
            return $this;
        }

        if (isset($criteria['start_date'])) {
            $this->dataProvider->setEndDateGreaterThan($criteria['start_date']);
        }

        if (isset($criteria['end_date'])) {
            $this->dataProvider->setStartDateLessThan($criteria['end_date']);
        }

        $this->dataProvider->setMinDuration($criteria['T']);

        return $this;
    }

    /**
     * Check only method.
     * T must be equal or shorter than given date range.
     *
     * @param $criteria
     * @return bool
     */
    private function checkCriteria($criteria)
    {
        if (!is_array($criteria)) {
            throw new \RuntimeException('Criteria must be an array');
        }

        if (isset($criteria['start_date']) && isset($criteria['end_date']) && isset($criteria['T'])) {
            $start = \DateTime::createFromFormat(Trip::DATE_FORMAT, $criteria['start_date']);
            $end   = \DateTime::createFromFormat(Trip::DATE_FORMAT, $criteria['end_date']);
            if (!$start || !$end) {
                throw new \RuntimeException('Bad dates or missing');
            }
            if ($start > $end) {
                throw new \RuntimeException('Impossible date combination');
            }
            $diff = $end->diff($start);
            if ($diff->days < $criteria['T']) {
                throw new \RuntimeException('T exceeds specified date range');
            }
        }

        return true;
    }

}
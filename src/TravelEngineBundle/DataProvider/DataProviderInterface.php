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
 * Interface DataProviderInterface, the interface of a database 
 * system driver. 
 * The architecture was created with ODM/ORM query builder
 * in mind.
 * Please implement your own driver respecting this interface
 * and plug it in via services.
 * 
 * @package TravelEngineBundle\DataProvider
 */
interface DataProviderInterface
{
    /**
     * Enforcing the specified country on the dataset.
     */
    public function setCountry($country);

    /**
     * Enforcing the specified city on the dataset.
     */
    public function setCity($city);

    /**
     * Dataset records must have start_date >= given value.
     */
    public function setStartDateGreaterOrEqual($start);

    /**
     * Dataset records must have start_date < given value.
     */
    public function setStartDateLessThan($date);

    /**
     * Dataset records must have end_date <= given value.
     */
    public function setEndDateLessOrEqual($end);

    /**
     * Dataset records must have end_date > given value.
     */
    public function setEndDateGreaterThan($date);

    /**
     * Dataset records must have duration >= given value.
     * Duration is end_date - start_date.
     */
    public function setMinDuration($int);

    /**
     * Whether querying for cities or countries, query
     * allows universal matching.
     */
    public function setQuery($value);

    /**
     * Retrieving all travel data from the database,
     * using a database query, created via above methods.
     * 
     * @return TripCollector
     */
    public function getData();
}
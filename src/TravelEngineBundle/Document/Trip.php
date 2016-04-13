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

namespace TravelEngineBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Trip
 * @package TravelEngineBundle\Entity
 * @ODM\Document
 */
class Trip
{
    const DATE_FORMAT = 'd.m.Y';

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $country;

    /**
     * @ODM\Field(type="string")
     */
    protected $city;

    /**
     * @ODM\Field(type="date")
     */
    protected $startDate;

    /**
     * @ODM\Field(type="date")
     */
    protected $endDate;

    /**
     * Internal only.
     * TRUE = trip is promoted in search results.
     *
     * @var bool
     */
    protected $promoted = false;

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    
    /**
     * @param $criteria
     * @return bool
     */
    public function matches($criteria)
    {
        if (empty($criteria)) {
            return false;
        }

        foreach ($criteria as $column => $value) {
            $matching = false;
            switch ($column) {
                case 'promoted':
                    $matching = $value === $this->promoted;
                    break;
                case 'start_date':
                    $matching = $value === $this->startDate;
                    break;
                case 'end_date':
                    $matching = $value === $this->endDate;
                    break;
                case 'city':
                    $matching = $value === $this->city;
                    break;
                case 'country':
                    $matching = $value === $this->country;
                    break;
                case 'q':
                    $value = ucfirst($value);
                    $matching = !$value || $value === $this->country || $value === $this->city;
                    break;
            }
            if (false === $matching) {
                return false; // every column has to match
            }
        }

        return true;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $givenDate
     * @return bool
     */
    public function isStartDateLess($givenDate)
    {
        if (is_string($givenDate)) {
            $givenDate = \DateTime::createFromFormat(self::DATE_FORMAT, $givenDate);
        }

        $startDate = \DateTime::createFromFormat(self::DATE_FORMAT, $this->startDate);

        return $startDate < $givenDate;
    }

    /**
     * @param $givenDate
     * @return bool
     */
    public function isEndDateGreater($givenDate)
    {
        if (is_string($givenDate)) {
            $givenDate = \DateTime::createFromFormat(self::DATE_FORMAT, $givenDate);
        }

        $endDate = \DateTime::createFromFormat(self::DATE_FORMAT, $this->endDate);

        return $endDate > $givenDate;
    }

    /**
     * Returns a distance in days between the start date and given date.
     *
     * @param $givenDate
     * @return mixed
     */
    public function getStartDateDiff($givenDate)
    {
        if (is_string($givenDate)) {
            $givenDate = \DateTime::createFromFormat(self::DATE_FORMAT, $givenDate);
        }

        $startDate = \DateTime::createFromFormat(self::DATE_FORMAT, $this->startDate);

        return abs($startDate->diff($givenDate)->days);
    }

    /**
     * Returns a distance in days between the end date and given date.
     *
     * @param $givenDate
     * @return mixed
     */
    public function getEndDateDiff($givenDate)
    {
        if (is_string($givenDate)) {
            $givenDate = \DateTime::createFromFormat(self::DATE_FORMAT, $givenDate);
        }

        $endDate = \DateTime::createFromFormat(self::DATE_FORMAT, $this->endDate);

        return abs($endDate->diff($givenDate)->days);
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        $startDate = \DateTime::createFromFormat(self::DATE_FORMAT, $this->startDate);
        $endDate   = \DateTime::createFromFormat(self::DATE_FORMAT, $this->endDate);

        return $endDate->diff($startDate)->days + 1;
    }

    /**
     * @return boolean
     */
    public function isPromoted()
    {
        return $this->promoted;
    }

    /**
     * @param boolean $promoted
     * @return $this
     */
    public function setPromoted($promoted)
    {
        $this->promoted = $promoted;

        return $this;
    }


}

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

use Doctrine\ODM\MongoDB\DocumentManager;

class MongoDataProvider implements DataProviderInterface
{
    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var \Doctrine\ODM\MongoDB\Query\Builder
     */
    private $queryBuilder;

    public function __construct(DocumentManager $manager)
    {
        $this->manager      = $manager;
        $this->queryBuilder = $this->createQueryBuilder();
    }

    public function setCountry($country)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('country')
                ->equals($country)
        );
    }

    public function setCity($city)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('city')
                ->equals($city)
        );
    }


    /**
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getData()
    {
        $result             = $this->queryBuilder->getQuery()->execute();
        $this->queryBuilder = $this->createQueryBuilder(); // reset criteria
        return $result;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    private function createQueryBuilder()
    {
        return $this->manager->createQueryBuilder('TravelEngineBundle:Trip');
    }

    public function setStartDateGreaterOrEqual($start)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('start_date')
                ->gte($start)
        );
    }

    public function setEndDateLessOrEqual($end)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('end_date')
                ->lte($end)
        );
    }

    public function setMinDuration($int)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('duration')// @todo Compute as end_date-start_date expr.
                ->gte($int)
        );
    }

    public function setStartDateLessThan($date)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('start_date')
                ->lt($date)
        );
    }

    public function setEndDateGreaterThan($date)
    {
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('end_date')
                ->gt($date)
        );
    }

    public function setQuery($value)
    {
        if (!$value) {
            return;
        }
        $this->queryBuilder->addAnd(
            $this->queryBuilder
                ->expr()
                ->field('city')
                ->equals($value)
                ->addOr(
                    $this->queryBuilder
                        ->expr()
                        ->field('country')
                        ->equals($value)
                )
        );
    }


}
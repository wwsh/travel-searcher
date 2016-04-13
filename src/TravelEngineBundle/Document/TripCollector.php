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

class TripCollector implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var Trip[]
     */
    private $collection = [];

    /**
     * @var bool
     */
    private $valid = true;

    public function __construct($data = [])
    {
        if (!is_array($data)) {
            throw new \RuntimeException('Bad data');
        }

        if (empty($data)) {
            return;
        }

        foreach ($data as $row) {
            $this->addTrip($row);
        }
    }

    public function addTrip($data)
    {
        if (is_array($data)) {
            $this->collection[] = TripFactory::create(
                $data['country'],
                $data['city'],
                $data['start_date'],
                $data['end_date']
            );

            return $this;
        }

        $this->collection[] = $data;

        return $this;
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        $this->valid = !!next($this->collection);

        return;
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return $this->valid;
    }

    public function rewind()
    {
        reset($this->collection);
        $this->valid = true;
    }

    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    /**
     * @param $criteria
     * @return $this|TripCollector
     */
    public function having($criteria)
    {
        $result = new self();

        if (empty($criteria)) {
            return $this;
        }

        foreach ($this->collection as $trip) {
            if ($trip->matches($criteria)) {
                $result->addTrip($trip);
            }
        }

        return $result;
    }

    public function count()
    {
        return count($this->collection);
    }

    public function unshiftTrip($trip)
    {
        array_unshift($this->collection, $trip);
    }


}
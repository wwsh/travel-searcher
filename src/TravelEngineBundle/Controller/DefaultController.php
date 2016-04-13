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

namespace TravelEngineBundle\Controller;

use Mockery\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TravelEngineBundle\Document\Trip;
use TravelEngineBundle\Document\TripCollector;
use TravelEngineBundle\Form\SearchType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $params = $request->query->get('search');

        $params = $this->setupDefaultParams($params);

        $form = $this->createForm(SearchType::class, $params);

        $vars = [
            'form' => $form->createView()
        ];

        try {
            $vars['result'] = $this->get('travel.searcher')->search($form->getData());
        } catch (\RuntimeException $e) {
            $vars['message'] = sprintf('Nieoczekiwany błąd: %s', $e->getMessage());
        }


        return $this->render('TravelEngineBundle:Default:index.html.twig', $vars);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function setupDefaultParams($params)
    {
        if (!isset($params['T'])) {
            $params['T'] = 14;
        }

        if (!isset($params['start_date'])) {
            $params['start_date'] = (new \DateTime())->format(Trip::DATE_FORMAT);
        }

        if (!isset($params['end_date'])) {
            $endDate  = new \DateTime();
            $interval = new \DateInterval('P' . $params['T'] . 'D');
            $endDate->add($interval);
            $params['end_date'] = $endDate->format(Trip::DATE_FORMAT);
        }
        
        return $params;
    }
}

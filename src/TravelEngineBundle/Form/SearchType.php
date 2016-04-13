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

namespace TravelEngineBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q', TextType::class, ['required'=>false])
                ->add('start_date', TextType::class)
                ->add('end_date', TextType::class)
                ->add('T', ChoiceType::class, [
                    'choices' => [
                        '1 dzień' => 1,
                        '2 dni'   => 2,
                        '3 dni'   => 3,
                        '4 dni'   => 4,
                        '5 dni'   => 5,
                        '6 dni'   => 6,
                        '7 dni'   => 7,
                        '8 dni'   => 8,
                        '9 dni'   => 9,
                        '10 dni'  => 10,
                        '11 dni'  => 11,
                        '12 dni'  => 12,
                        '13 dni'  => 13,
                        '14 dni'  => 14,
                        '15 dni'  => 15,
                        '16 dni'  => 16,
                        '17 dni'  => 17,
                        '18 dni'  => 18,
                        '19 dni'  => 19,
                        '20 dni'  => 20,
                        '30 dni'  => 30,
                    ]
                ]);
    }
}

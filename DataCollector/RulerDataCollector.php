<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoathis\Bundle\RulerBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

use Hoathis\Bundle\RulerBundle\Ruler\LoggedRuler;

/**
 * Ruler data collector.
 *
 * @author     Kévin Gomez <contact@kevingomez.fr>
 * @copyright  Copyright © 2007-2015 Hoa community
 * @license    New BSD License
 */
class RulerDataCollector implements DataCollectorInterface
{
    /**
     * @var LoggedRuler
     */
    private $ruler;

    public function __construct(LoggedRuler $ruler)
    {
        $this->ruler = $ruler;
    }

    /**
     * @inheritDoc
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $statistics = $this->ruler->getStatistics();
        $totalTime  = 0;

        foreach ($statistics as $rule => $data) {
            $ruleTime = array_sum($data['durations']);

            $statistics[$rule]['sum_duration'] = $ruleTime;
            $statistics[$rule]['avg_duration'] = $ruleTime / $data['count'];
            $totalTime                        += $ruleTime;
        }

        $this->data = array(
            'statistics' => $statistics,
            'count'      => array_sum(array_column($statistics, 'count')),
            'time'       => $totalTime,
        );
    }

    /**
     * The total number of executed rules.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->data['count'];
    }

    /**
     * The time spent executing rules.
     *
     * @return int
     */
    public function getTime()
    {
        return $this->data['time'];
    }

    /**
     * The statistics recorded during the execution.
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->data['statistics'];
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'hoathis.ruler';
    }
}

<?php

namespace Hoathis\Bundle\RulerBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

use Hoathis\Bundle\RulerBundle\Ruler\LoggedRuler;

/**
 * Ruler data collector.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
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

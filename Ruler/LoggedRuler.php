<?php

namespace Hoathis\Bundle\RulerBundle\Ruler;

use Hoa\Ruler\Context;
use Hoa\Ruler\Ruler;

/**
 * A special Ruler that logs a lot of stuff.
 *
 * @note Should only be used in developement or test environments.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class LoggedRuler extends Ruler
{
    private $statistics = [];

    /**
     * @inheritDoc
     */
    public function assert($rule, Context $context = null)
    {
        $ruleAsString = (string) $rule;

        if (empty($this->statistics[$ruleAsString])) {
            $this->statistics[$ruleAsString] = [
                'count'     => 0,
                'durations' => [],
            ];
        }

        $start = microtime(true);
        $result = parent::assert($rule, $context);
        $duration = microtime(true) - $start;

        $this->statistics[$ruleAsString]['count'] += 1;
        $this->statistics[$ruleAsString]['durations'][] = $duration;

        return $result;
    }

    /**
     * Returns the statistics recorded during the execution.
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}

<?php

namespace Hoathis\Bundle\RulerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function  __construct($debug)
    {
        $this->debug = (bool) $debug;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hoathis_ruler');

        $this->addGeneralNode($rootNode);

        return $treeBuilder;
    }

    protected function addGeneralNode(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->booleanNode('debug')->defaultValue($this->debug)->end()
            ->end()
        ;

        return $rootNode;
    }
}

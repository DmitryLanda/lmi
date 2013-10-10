<?php

namespace Lmi\Bundle\SchoolBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lmi_school');

        $rootNode
            ->children()
                ->scalarNode('menu_file')->end()
                ->scalarNode('default_image')->end()
                ->arrayNode('yandex')
                    ->children()
                        ->arrayNode('fotki')
                            ->children()
                                ->scalarNode('image_url_pattern')->end()
                                ->scalarNode('album_url_pattern')->end()
                                ->scalarNode('format')->end()
                                ->scalarNode('default_album')->end()
                                ->arrayNode('album_map')
                                    ->prototype('scalar')
//                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

<?php

namespace Lmi\Bundle\SchoolBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LmiSchoolExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $ymlParser = new Yaml();
        $menu = $ymlParser->parse($config['menu_file']);
        $container->setParameter('lmi_school.parameter.menu', $menu);

        $container->setParameter('yandex', $config['yandex']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('managers.xml');
        $loader->load('services.xml');
        $loader->load('twig.xml');
        $loader->load('forms.xml');
    }
}

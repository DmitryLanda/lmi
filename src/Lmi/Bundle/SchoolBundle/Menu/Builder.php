<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/

namespace Lmi\Bundle\SchoolBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class Builder extends ContainerAware
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $this->factory = $factory;
        $menuItems = $this->container->getParameter('lmi_school.parameter.menu');
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        foreach ($menuItems as $key => $value) {
            $this->buildNode($menu, $key, $value);
        }

        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     * @param string $key
     * @param mixed $value
     */
    private function buildNode(ItemInterface $menu, $key, $value)
    {
        if (!is_array($value)) {
            $menu->addChild($key, array('route' => $value));
        } else {
            $subMenu = $this->factory->createItem($key, array(
                'uri' => '#',
                'attributes' => array('class' => 'dropdown'),
                'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown')
            ));
            foreach ($value as $subKey => $subValue) {
                $this->buildNode($subMenu, $subKey, $subValue);
            }
            $subMenu->setChildrenAttribute('class', 'dropdown-menu');
            $menu->addChild($subMenu);

        }
    }
}

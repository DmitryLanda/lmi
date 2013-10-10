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
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $menuItems;

    /**
     * @param FactoryInterface $factory
     * @param array $menuItems
     */
    public function __construct(FactoryInterface $factory, array $menuItems)
    {
        $this->factory = $factory;
        $this->menuItems = $menuItems;
    }

    /**
     * @param Request $request
     * @return ItemInterface
     */
    public function createLeftMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());

        foreach ($this->menuItems as $key => $value) {
            $this->buildNode($menu, $key, $value);
        }

        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');

        return $menu;
    }

    /**
     * @param ItemInterface $mainMenu
     * @param Request $request
     * @return ItemInterface
     */
    public function createBreadcrumbMenu(ItemInterface $mainMenu, Request $request)
    {
        $currentItem = $mainMenu->getCurrentItem();
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        // this item will always be displayed
        $menu->addChild('Главная', array('route' => 'homepage'));

        //todo fix losing parent for some pages not listed in menu
        if (!$currentItem) {
            $currentItem = $menu;
        }
        foreach ($currentItem->getBreadcrumbsArray() as $key => $value) {
            if ($key == 'root') {
                continue;
            }
            $this->buildBreadcrumbNode($menu, $key, $value);
        }

        $menu->setChildrenAttribute('style', 'background: none;');
        $menu->setChildrenAttribute('class', 'breadcrumb pull-left');

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
            $menu->addChild($key, $this->parseToOptions($value));
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

    /**
     * @param string $value
     * @return array
     */
    private function parseToOptions($value)
    {
        $options = explode(',', $value);
        $route = array_shift($options);
        $routeParameters = array();
        foreach ($options as $option) {
            list($key, $parameter) = explode(':', $option);
            $routeParameters[$key] = $parameter;
        }

        return array(
            'route' => $route,
            'routeParameters' => $routeParameters
        );
    }

    /**
     * @param ItemInterface $menu
     * @param $key
     * @param $value
     */
    private function buildBreadcrumbNode(ItemInterface $menu, $key, $value)
    {
        if (!is_array($value)) {
            $menu->addChild($key, array('uri' => $value));
        } else {
            $subMenu = $this->factory->createItem($key, array('uri' => '#'));
            foreach ($value as $subKey => $subValue) {
                $this->buildNode($subMenu, $subKey, $subValue);
            }
            $menu->addChild($subMenu);

        }
    }

}

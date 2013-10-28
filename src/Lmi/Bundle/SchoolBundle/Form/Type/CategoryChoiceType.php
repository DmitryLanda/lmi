<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class CategoryChoiceType extends AbstractType
{
    private $categoryChoices = array();

    public function __construct(array $categoryChoices)
    {
        $this->categoryChoices = $categoryChoices;
    }

    /**
     * {@inherit}
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->categoryChoices
        ));
    }

    /**
     * {@inherit}
     *
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inherit}
     *
     * @return string
     */
    public function getName()
    {
        return 'lmi_category_choices';
    }
}

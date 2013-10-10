<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Form\Type;

use Lmi\Bundle\SchoolBundle\Form\Transformer\ImageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class YaImageLoaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'ya_image', array(
                'attr' => array(
                    'multiple' => true
                )
            ))
            ->add('selected_images', 'choice', array(
                'multiple' => true,
                'required' => false
            ));
    }

    /**
     * {@inheritdoc}
     *
     * @return null|string|FormTypeInterface
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'ya_image_loader';
    }
}

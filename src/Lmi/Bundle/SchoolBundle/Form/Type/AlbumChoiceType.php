<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Form\Type;

use Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager\AlbumManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class AlbumChoiceType extends AbstractType
{
    /**
     * @var array
     */
    private $albums = array('new' => 'Создать новый альбом');

    /**
     * @param AlbumManagerInterface $albumManager
     */
    public function __construct(AlbumManagerInterface $albumManager)
    {
        foreach ($albumManager->getAll() as $album) {
            $this->albums[$album->getId()] = $album->getName();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'file', array(
            'required' => true,
            'label' => 'lmi.school.common.image'
        ))
        ->add('album', 'choice', array(
            'choices' => $this->albums,
            'required' => false,
            'label' => 'lmi.school.common.album'
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
     * {@inherit}
     *
     * @return string
     */
    public function getName()
    {
        return 'yandex_fotki_album_choices';
    }
}

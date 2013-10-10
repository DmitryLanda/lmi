<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'text', array(
                'label' => 'lmi.school.common.contact.type',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.contact.type_placeholder'
                )
            ))
            ->add('value', 'text', array(
                'label' => 'lmi.school.common.contact.value',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.contact.value_placeholder'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_contact';
    }
}

<?php

namespace Lmi\Bundle\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegardType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'lmi.school.common.regard.name',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.regard.name_placeholder'
                ))
            )
            ->add('priority', 'choice', array(
                'label' => 'lmi.school.common.regard.priority_title',
                'choices' => array(
                    0 => 'lmi.school.common.regard.priority.high',
                    1 => 'lmi.school.common.regard.priority.medium',
                    2 => 'lmi.school.common.regard.priority.low',
                    3 => 'lmi.school.common.regard.priority.empty',
                )
            ))
            ->add('description', 'textarea', array(
                'label' => 'lmi.school.common.regard.description',
            ))
            ->add('date', 'date', array(
                'label' => 'lmi.school.common.regard.date',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'lmi.school.common.date_placeholder'
                ))
            );
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lmi\Bundle\SchoolBundle\Entity\Regard'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lmi_bundle_schoolbundle_regard';
    }
}
